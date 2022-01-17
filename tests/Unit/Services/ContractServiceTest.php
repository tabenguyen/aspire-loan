<?php

namespace Tests\Unit\Services;

use App\Models\Contract;
use App\Models\LoanTerm;
use App\Models\User;
use App\Services\ContractService;
use App\Services\LoanTermService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\BaseTestCase;
use Tests\TestCase;

class ContractServiceTest extends TestCase
{
    /**
     * @var ContractService
     */
    protected $service;

    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ContractService::class);
    }
    public function test_apply()
    {
        $user = User::factory()->create();
        $term = LoanTerm::factory()->create([
            'apr' => 12,
            'fee' => 1000
        ]);
        $contract = $this->service->apply($user->id, $term->id, 500000, Carbon::now()->subWeek(5));
        $this->assertNotEmpty($contract);

        return $contract;
    }

    /**
     * @depends test_apply
     *
     * @return void
     */
    public function test_getCurrentStatus_amor_success($contract)
    {
        $user = User::factory()->create();
        $term = LoanTerm::factory()->create([
            'apr' => 52,
            'fee' => 1000,
            'interest_type' => LoanTerm::INTEREST_TYPE_AMOR,
            'length' => 6,
        ]);
        $contract = $this->service->apply($user->id, $term->id, 100000, Carbon::now()->subWeek(5));
        $status = $this->service->getContractStatus($contract, Carbon::now());
        $this->assertEquals($status->getDebtAmount(), 100000);
        $this->assertEquals($status->getFee(), 1000);
        $this->assertEquals($status->getInterest(), 1000);
    }


    /**
     * @depends test_apply
     *
     * @return void
     */
    public function test_getCurrentStatus_nonamor_success($contract)
    {
        $user = User::factory()->create();
        $term = LoanTerm::factory()->create([
            'apr' => 52,
            'fee' => 1000,
            'interest_type' => LoanTerm::INTEREST_TYPE_NON_AMOR,
            'length' => 6,
        ]);
        $contract = $this->service->apply($user->id, $term->id, 100000, Carbon::now()->subWeek(5));
        $status = $this->service->getContractStatus($contract, Carbon::now());
        $this->assertEquals($status->getDebtAmount(), 100000);
        $this->assertEquals($status->getFee(), 1000);
        $this->assertEquals($status->getInterest(), 1000);
    }
}