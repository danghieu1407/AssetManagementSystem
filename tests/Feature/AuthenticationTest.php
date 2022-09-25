<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_user_can_login()
    {
        $body = [
            "username" => "bichvht",
            "password" => "12345"
        ];
        $response = $this->json('POST', 'api/login', $body);

        $response->assertStatus(200);
    }

    public function test_a_user_login_wrong_password()
    {
        $body = [
            "username" => "bichvht",
            "password" => "1230594"
        ];

        $this->json('POST', 'api/login', $body, ['Accept' => 'application/json'])
            ->assertStatus(404);
    }

    public function test_a_user_login_wrong_username()
    {
        $body = [
            "username" => "asgstesdt",
            "password" => "12345"
        ];

        $this->json('POST', 'api/login', $body, ['Accept' => 'application/json'])
            ->assertStatus(404);
    }

    public function test_a_user_login_wrong_username_and_password()
    {
        $body = [
            "username" => "isobelmadd1",
            "password" => "sddfgdydyerrt"
        ];

        $this->json('POST', 'api/login', $body, ['Accept' => 'application/json'])
            ->assertStatus(404);
    }

    public function test_a_user_login_wrong_username_and_password_and_state()
    {
        $body = [
            "username" => "tinht",
            "password" => "12345",
        ];

        $this->json('POST', 'api/login', $body, ['Accept' => 'application/json'])
            ->assertStatus(404);
    }

    public function test_log_out_successfully()
    {
        $response = $this->postJson(
            'api/login',
            [
                'username' => 'tuandc',
                'password' => '12345',
            ]
        );
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $response = $this->getJson('/api/logout', [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200);
    }

    public function test_get_information_successfully()
    {
        $response = $this->postJson('api/login', [
            'username' => 'bichvht',
            'password' => '12345',
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $response = $this->getJson('api/user-information', [
            'Authorization' => "Bearer $token"
        ]);
        $response->assertStatus(200)->assertJsonFragment([
            "username" => "bichvht"
        ]);
    }
}
