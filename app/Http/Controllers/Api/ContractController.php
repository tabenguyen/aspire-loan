<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContractRequest;
use App\Http\Requests\CreateLoanTermRequest;
use App\Http\Requests\CreatePayRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\ContractCollection;
use App\Http\Resources\ContractResource;
use App\Http\Resources\ContractStatusResource;
use App\Http\Resources\LoanTermCollection;
use App\Http\Resources\LoanTermResource;
use App\Http\Resources\RepaymentCollection;
use App\Http\Resources\RepaymentResource;
use App\Http\Resources\UserResource;
use App\Models\Contract;
use App\Models\User;
use App\Services\contractService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContractController extends Controller
{
    private $contractService;

    public function __construct(ContractService $contractService)
    {
        $this->contractService = $contractService;
    }

    public function byCustomer(Request $request)
    {
        return new ContractCollection($request->user()->contracts);
    }

    public function index(Request $request)
    {
        $qb = $this->contractService->search($request->all());
        return new ContractCollection($qb->paginate(10));
    }

    public function store(CreateContractRequest $request)
    {
        $params = $request->all();
        $params['user_id'] = $request->user()->id;
        $term = $this->contractService->save($params);
        return new LoanTermResource($term);
    }

    public function detail(Request $request, Contract $contract)
    {
        return new ContractResource($contract);
    }

    public function repayments(Request $request, Contract $contract)
    {
        return new RepaymentCollection($contract->repayments()->paginate(10));
    }

    public function status(Request $request, Contract $contract)
    {
        return new ContractStatusResource($this->contractService->getContractStatus($contract, Carbon::now()));
    }

    public function pay(CreatePayRequest $request, Contract $contract)
    {
        $repayment = $this->contractService->submitRepayment($contract, $request->user(), $request->amount);
        if ($repayment) {
            return new RepaymentResource($repayment);
        }

        return response()->json(['message' => 'Your amount was not correct'], 422);
    }
}