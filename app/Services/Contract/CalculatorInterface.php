<?php
namespace App\Services\Contract;

use App\Models\Contract;
use Carbon\Carbon;

interface CalculatorInterface
{
    public function estimateInterestAmount(Contract $contract, Carbon $to): int;
}