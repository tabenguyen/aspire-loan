<?php

namespace App\Http\Controllers;

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
        $qb = $this->loantermService->getAll();
        return response()->json(new LoanTermCollection($qb->paginate(10)));
    }

    public function store(CreateLoanTermRequest $request)
    {
        $term = $this->loantermService->createTerm($request->parameters());
        return new LoanTermResource($term);
    }
}
