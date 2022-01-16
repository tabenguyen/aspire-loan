<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoanTermRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoanTermCollection;
use App\Http\Resources\LoanTermResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\contractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ContractController extends Controller
{
    private $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    public function apply(CreateLoanTermRequest $request)
    {
        $term = $this->contractService->createTerm($request->parameters());
        return new LoanTermResource($term);
    }
}
