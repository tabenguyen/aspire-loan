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
        return ($contract->amount * ($contract->loanTerm->apr / 100) / 52);
    }
}