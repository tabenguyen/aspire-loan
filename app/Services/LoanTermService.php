<?php

namespace App\Services;

use App\Http\Requests\CreateLoanTermRequest;
use App\Models\LoanTerm;
use Illuminate\Support\Facades\Log;

class LoanTermService
{
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = LoanTerm::query();
    }

    /**
     * Get all loanterm with order by created ASC
     *
     * @return void
     */
    public function getAll()
    {
        return $this->queryBuilder->orderBy('created_at', 'DESC');
    }

    /**
     * Create a new loanterm
     *
     * @param array $params
     * @return void
     */
    public function createTerm(array $params)
    {
        try {
            $term = $this->queryBuilder->create($params);
            return $term;
        } catch (\Exception $ex) {
            Log::error($ex);
            return null;
        }
    }
}
