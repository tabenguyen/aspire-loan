<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLoanTermRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoanTermCollection;
use App\Http\Resources\LoanTermResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\LoanTermService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoanTermController extends Controller
{
    private $loantermService;

    public function __construct(LoanTermService $loantermService)
    {
        $this->loantermService = $loantermService;
    }

    public function index(Request $request)
    {
        $qb = $this->loantermService->search($request->all());
        return new LoanTermCollection($qb->paginate(10));
    }

    public function store(CreateLoanTermRequest $request)
    {
        $term = $this->loantermService->save($request->parameters());
        return new LoanTermResource($term);
    }
}
