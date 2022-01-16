<?php
namespace App\Services\Contract\InterestRepayment;

use App\Models\Contract;
use App\Models\Transaction;
use App\Services\Contract\CalculatorInterface;
use Carbon\Carbon;

class AmortizedLoan implements CalculatorInterface
{
    public function estimateInterestAmount(Contract $contract, Carbon $to): int
    {
        $term = $contract->loanTerm;
        $repaymentTotal = $contract->transactions()->where('type', Transaction::TYPE_REPAYMENT)->sum('amount');
        $repaymentRemain = $contract->amount - $repaymentTotal;

        return ($repaymentRemain * ($term->apr / 100) / 52);
    }
}