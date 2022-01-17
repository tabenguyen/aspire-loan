<?php

namespace App\Models;

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
}
