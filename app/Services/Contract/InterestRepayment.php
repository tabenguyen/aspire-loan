<?php
namespace App\Services\Contract;

use App\Models\Contract;
use Carbon\Carbon;

abstract class InterestRepayment
{
    abstract function createCaculator(): CalculatorInterface;

    public function estimateAmount(Contract $contract, Carbon $to = null)
    {
        if (empty($to)) {
            $to = Carbon::now();
        }
        return $this->createCaculator()->estimateInterestAmount($contract, $to);
    }
}