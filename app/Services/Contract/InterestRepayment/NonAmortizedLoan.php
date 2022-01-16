<?php
namespace App\Services\Contract\InterestRepayment;

use App\Models\Contract;
use App\Models\Transaction;
use App\Services\Contract\CalculatorInterface;
use Carbon\Carbon;

class NonAmortizedLoan implements CalculatorInterface
{
    public function estimateInterestAmount(Contract $contract, Carbon $to): int
    {
        $term = $contract->loanTerm;
        $total = (($term->apr/100)*$contract->amount/52)*$contract->length;
        $transfered = $contract->transactions()->where('type', Transaction::TYPE_INTEREST)->sum('amount');
        return $total - $transfered;
    }
}