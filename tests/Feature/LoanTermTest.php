<?php

namespace Tests\Feature;

use App\Models\LoanTerm;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoanTermTest extends BaseTestCase
{
    protected $user;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::find(1);
    }

    public function test_index()
    {
        LoanTerm::factory()->count(10)->create();
        $response = $this->actingAs($this->user)->get('/api/loan-terms');
        $response->assertJson(fn (AssertableJson $json) =>
            $json
                ->has(10)
                ->first(fn ($json) =>
                $json
                    ->has('apr')
                    ->has('length')
                    ->has('fee')
                    ->has('interest_type')
                    ->etc()
            )
        );
    }

    public function store_data_provider_success()
    {
        return [
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'fee' => 300,
                    'interest_type' => 1,
                ],
            ],
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'fee' => 300,
                    'interest_type' => 0,
                ],
            ],
        ];
    }

    /**
     * @dataProvider store_data_provider_success
     *
     * @return void
     */
    public function test_store_success($data)
    {
        $response = $this->actingAs($this->user)->post('/api/loan-terms', $data);
        $response->assertStatus(201);
        $response->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data')
                ->has('data.apr')
                ->has('data.length')
                ->has('data.fee')
                ->has('data.interest_type')
        );
    }

    public function store_data_provider_fail_missing()
    {
        return [
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'fee' => 300,
                ],
                'missing' => 'interest_type',
            ],
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'interest_type' => 0,
                ],
                'missing' => 'fee',
            ],
            [
                'data' => [
                    'apr' => 1,
                    'fee' => 0,
                    'interest_type' => 0,
                ],
                'missing' => 'length',
            ],
            [
                'data' => [
                    'interest_type' => 1,
                    'length' => 200,
                    'fee' => 0,
                ],
                'missing' => 'apr',
            ],
        ];
    }


    /**
     * @dataProvider store_data_provider_fail_missing
     *
     * @return void
     */
    public function test_store_fail_missing($data, $missing)
    {
        $response = $this->actingAs($this->user)->post('/api/loan-terms', $data);
        $response->assertStatus(422);
        $response->assertJson(
            fn (AssertableJson $json) =>
                $json->has('message')
                    ->has('errors', 1)
                    ->has('errors.' . $missing)
                    ->etc()
        );
    }


    /**
     * @return void
     */
    public function test_store_fail_interst_type()
    {
        $response = $this->actingAs($this->user)->post('/api/loan-terms', [
            'apr' => 1,
            'length' => 200,
            'fee' => 300,
            'interest_type' => 3,
        ]);
        $response->assertStatus(422);
        $response->assertJson(
            fn (AssertableJson $json) =>
                $json->has('message')
                    ->has('errors', 1)
                    ->has('errors.interest_type')
                    ->etc()
        );
    }


    public function store_data_provider_fail_value_type()
    {
        return [
            [
                'data' => [
                    'apr' => 'Text',
                    'length' => 200,
                    'fee' => 300,
                    'interest_type' => 1,
                ],
                'error' => 'apr',
            ],
            [
                'data' => [
                    'apr' => 1,
                    'length' => 'Text',
                    'fee' => 300,
                    'interest_type' => 1,
                ],
                'error' => 'length',
            ],
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'fee' => 'Text',
                    'interest_type' => 1,
                ],
                'error' => 'fee',
            ],
            [
                'data' => [
                    'apr' => 1,
                    'length' => 200,
                    'fee' => 300,
                    'interest_type' => 'Text',
                ],
                'error' => 'interest_type',
            ],
        ];
    }
    /**
     * @dataProvider store_data_provider_fail_value_type
     *
     * @return void
     */
    public function test_store_fail_value_type($data, $error)
    {
        $response = $this->actingAs($this->user)->post('/api/loan-terms', $data);
        $response->assertStatus(422);
        $response->assertJson(
            fn (AssertableJson $json) =>
                $json->has('message')
                    ->has('errors', 1)
                    ->has('errors.' . $error)
                    ->etc()
        );
    }
}
