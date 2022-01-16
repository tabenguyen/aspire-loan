<?php

namespace Tests\Unit\Services;

use App\Models\LoanTerm;
use App\Models\User;
use App\Services\LoanTermService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\BaseTestCase;
use Tests\TestCase;

class LoanTermServiceTest extends TestCase
{
    /**
     * @var LoanTermService
     */
    protected $service;

    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LoanTermService::class);
    }

    public function test_get_all()
    {
        $qb = $this->service->getAll();
        $this->assertEquals(0, $qb->count());
        LoanTerm::factory()->count(20)->create();
        $this->assertEquals(20, $qb->count());
        $ids = $qb->pluck('id');
        $this->assertTrue($ids->first() < $ids->last());
    }

    public function test_create_term_success()
    {
        $term = $this->service->createTerm([
            'apr' => 1,
            'length' => 200,
            'fee' => 300,
            'interest_type' => 1,
        ]);
        $this->assertNotEmpty($term);
        $this->assertGreaterThan(0, $term->id);
        $term = $this->service->createTerm([
            'apr' => 1,
            'length' => 200,
            'fee' => 300,
            'interest_type' => 0,
        ]);
        $this->assertNotEmpty($term);
        $this->assertGreaterThan(0, $term->id);
    }

    public function create_term_fail_dataprovider()
    {
        return [
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'fee' => 300,
                ]
            ],
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'interest_type' => 0,
                ]
            ],
            [
                'data' => [
                    'apr' => 1,
                    'fee' => 300,
                    'interest_type' => 0,
                ]
            ],
            [
                'data' => [
                    'length' => 200,
                    'fee' => 300,
                    'interest_type' => 0,
                ]
            ],
        ];
    }
    /**
     * @dataProvider create_term_fail_dataprovider
     *
     * @param [type] $data
     * @return void
     */
    public function test_create_term_fail($data)
    {
        $term = $this->service->createTerm($data);
        $this->assertEmpty($term);
    }
}
