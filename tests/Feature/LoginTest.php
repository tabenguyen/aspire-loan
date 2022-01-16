<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setBearerToken('');
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $response = $this->post('/api/login', [
                'email' => 'suppervisor@aspire.examlple',
                'password' => 'suppervisor'
        ]);
        $response->assertStatus(200);
    }

    public function test_me()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/api/me');
        $response->assertStatus(200);
    }
}
