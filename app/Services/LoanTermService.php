<?php

namespace App\Services;

use App\Http\Requests\CreateLoanTermRequest;
use App\Models\LoanTerm;
use Illuminate\Support\Facades\Log;

class LoanTermService extends BaseService
{
    public function createQueryBuilder()
    {
        return LoanTerm::query();
    }
}
