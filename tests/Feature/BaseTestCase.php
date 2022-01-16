<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->headers = [
            'Accept' => 'application/json'
        ];
        $token = $this->getBearerToken();
        if(!empty($token)) {
            $this->headers['Authorization'] = 'Bearer ' . $this->getBearerToken();
        }
        $this->withHeaders($this->headers);
    }

    protected function setBearerToken(string $token)
    {
        Cache::add('auth_token', $token);
    }

    protected function getBearerToken(): string
    {
        return Cache::get('auth_token') ?? '';
    }
}
