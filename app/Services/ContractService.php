<?php

namespace App\Services;

use App\Http\Requests\CreateLoanTermRequest;
use App\Models\Contract;
use App\Models\LoanTerm;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Contract\AmortizedLoanInterest;
use App\Services\Contract\InterestRepayment;
use App\Services\Contract\NonAmortizedLoanInterest;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;

class ContractService
{
    /**
     * Get processor base on type of interest
     *
     * @param Contract $contract
     * @return InterestRepayment
     */
    public function getInterestProcess(Contract $contract): InterestRepayment
    {
        if ($contract->loanTerm->interest_type == LoanTerm::INTEREST_TYPE_AMOR ) {
            return app(AmortizedLoanInterest::class);
        }
        if ($contract->loanTerm->interest_type == LoanTerm::INTEREST_TYPE_NON_AMOR ) {
            return app(NonAmortizedLoanInterest::class);
        }
    }

    /**
     * User apply to an loan term
     *
     * @param integer $userId
     * @param integer $loanTermId
     * @param integer $amount
     * @param Carbon $startDate
     * @return void
     */
    public function apply(int $userId, int $loanTermId, int $amount, Carbon $startDate)
    {
        try {
            $contract = Contract::create([
                'user_id' => $userId,
                'loan_term_id' => $loanTermId,
                'amount' => $amount,
                'start_date' => $startDate,
                'status' => Contract::STATUS_PENDING,
            ]);
            return $contract;
        } catch (\Exception $ex) {
            Log::error($ex);
            return null;
        }
    }

    /**
     * Retreive current status of a contract
     *
     * @param Contract $contract
     * @param Carbon $to
     * @return void
     */
    public function getCurrentStatus(Contract $contract, Carbon $to)
    {
        $debt = $contract->amount - $contract->transactions()->sum('amount');
        $fee = $contract->loanTerm->fee - $contract->transactions()->where('type', Transaction::TYPE_FEE)->sum('amount');
        $interest = $this->getInterestProcess($contract)->estimateAmount($contract, $to);
        return [
            'weekNo' => $to->diffInWeeks($contract->start_date),
            'debtAmount' => $debt,
            'fee' => $fee,
            'interest' => $interest
        ];
    }
}
