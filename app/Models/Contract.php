<?php

namespace App\Models;

use App\Models\Virtuals\ContractStatus;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;

    use HasFactory;

    protected $fillable = ['user_id', 'loan_term_id', 'amount', 'start_date', 'status'];
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->status = $model->status ?? self::STATUS_INACTIVE;
        });
    }

    public function loanTerm(): BelongsTo
    {
        return $this->belongsTo(LoanTerm::class);
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class);
    }

    public function getRepaymentAmount(): int
    {
        return (int)round($this->amount / $this->loanTerm->length, PHP_ROUND_HALF_UP);
    }

    public function sumTransaction(string $type = null)
    {
        $paid = 0;
        $this->repayments->map(function($repayment) use(&$paid, $type) {
            $qb = $repayment->transactions();
            if (!empty($type)) {
                $qb->where('type', $type);
            }
            $paid += $qb->sum('amount');
        });

        return $paid;
    }

    public function getRepaymentPaid(): int
    {
        return $this->sumTransaction(Transaction::TYPE_REPAYMENT);
    }

    public function getBalance(): int
    {
        return $this->amount - $this->getRepaymentPaid();
    }

    public function getRepaymentPerPay(): int
    {
        return (int)round($this->amount / $this->loanTerm->length, PHP_ROUND_HALF_UP);
    }

    /**
     * Retreive current status of a contract
     *
     * @param Contract $contract
     * @param Carbon $to
     * @return ContractStatus
     */
    public function getContractStatus(Contract $contract, Carbon $to): ContractStatus
    {
        $debt = $contract->getBalance();
        $fee = $contract->loanTerm->fee - $contract->sumTransaction(Transaction::TYPE_FEE);
        $interest = $this->getInterestProcess($contract)->estimateAmount($contract, $to);
        $status = new ContractStatus();
        $status->setDebtAmount($debt);
        $status->setRepaymentAmount($contract->getRepaymentAmount());
        $status->setFee($fee);
        $status->setInterest($interest);

        return $status;
    }
}
