<?php
namespace App\Services\Contract;

use App\Models\Contract;
use App\Services\Contract\InterestRepayment\AmortizedLoan;
use App\Services\Contract\InterestRepayment\NonAmortizedLoan;
use Carbon\Carbon;

class NonAmortizedLoanInterest extends InterestRepayment
{
    public function createCaculator(): CalculatorInterface
    {
        return new NonAmortizedLoan();
    }
}