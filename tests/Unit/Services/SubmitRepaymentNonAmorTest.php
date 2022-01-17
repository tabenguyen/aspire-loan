<?php

namespace Tests\Unit;

use App\Models\Contract;
use App\Models\LoanTerm;
use App\Models\User;
use App\Services\ContractService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\BaseTestCase;
use Tests\TestCase;

class SubmitRepaymentNonAmorTest extends BaseTestCase
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

    public function test_SubmitRepayment_nonamor_fail()
    {
        $borrowAmount = 1000000;
        $user = User::factory()->create();
        $term = LoanTerm::factory()->create([
            'apr' => 52,
            'fee' => 1000,
            'interest_type' => LoanTerm::INTEREST_TYPE_NON_AMOR,
            'length' => 20,
        ]);
        $contract = $this->service->apply($user->id, $term->id, $borrowAmount, Carbon::now()->subWeek(10));
        $repayment = $this->service->submitRepayment($contract, $user, 10);
        $this->assertFalse($repayment);
    }


    public function test_SubmitRepayment_nonamor_success_1st()
    {
        $borrowAmount = 1000000;
        $user = User::factory()->create();
        $term = LoanTerm::factory()->create([
            'apr' => 52,
            'fee' => 1000,
            'interest_type' => LoanTerm::INTEREST_TYPE_NON_AMOR,
            'length' => 20,
        ]);
        $contract = $this->service->apply($user->id, $term->id, $borrowAmount, Carbon::now()->subWeek(10));
        $amount = 61000;
        $repayment = $this->service->submitRepayment($contract, $user, $amount);
        $this->assertNotEmpty($repayment);
        $contract = Contract::find($contract->id);
        $this->assertEquals($contract->getBalance(), $contract->amount - $contract->getRepaymentPerPay());
    }

    public function test_SubmitRepayment_nonamor_success_2nd()
    {
        $borrowAmount = 1000000;
        $user = User::factory()->create();
        $term = LoanTerm::factory()->create([
            'apr' => 52,
            'fee' => 1000,
            'interest_type' => LoanTerm::INTEREST_TYPE_NON_AMOR,
            'length' => 20,
        ]);
        $contract = $this->service->apply($user->id, $term->id, $borrowAmount, Carbon::now()->subWeek(10));
        // 1st repayment
        $amount = 61000;
        $repayment = $this->service->submitRepayment($contract, $user, $amount);
        $this->assertNotEmpty($repayment);
        $contract = Contract::find($contract->id);
        $this->assertEquals($contract->getBalance(), $contract->amount - $contract->getRepaymentPerPay());

        // 2st repayment
        $amount = 60000;
        $repayment = $this->service->submitRepayment($contract, $user, $amount);
        $this->assertNotEmpty($repayment);
        $contract = Contract::find($contract->id);
        $this->assertEquals($contract->getBalance(), $contract->amount - 2 * $contract->getRepaymentPerPay());
    }


    public function test_SubmitRepayment_nonamor_success_full()
    {
        $borrowAmount = 1000000;
        $user = User::factory()->create();
        $term = LoanTerm::factory()->create([
            'apr' => 52,
            'fee' => 1000,
            'interest_type' => LoanTerm::INTEREST_TYPE_NON_AMOR,
            'length' => 2,
        ]);
        $contract = $this->service->apply($user->id, $term->id, $borrowAmount, Carbon::now()->subWeek(10));
        // 1st repayment
        $amount = 500000 + 1000 + 10000;
        $repayment = $this->service->submitRepayment($contract, $user, $amount);
        $this->assertNotEmpty($repayment);
        $contract = Contract::find($contract->id);
        $this->assertEquals($contract->getBalance(), $contract->amount - $contract->getRepaymentPerPay());

        // 2st repayment
        $amount = 500000 + 10000;
        $repayment = $this->service->submitRepayment($contract, $user, $amount);
        $this->assertNotEmpty($repayment);
        $contract = Contract::find($contract->id);
        $this->assertEquals($contract->getBalance(), $contract->amount - 2 * $contract->getRepaymentPerPay());

        // Assert contract
        $this->assertEquals($contract->getBalance(), 0);
    }
}
