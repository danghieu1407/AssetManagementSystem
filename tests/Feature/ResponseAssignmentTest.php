<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\AssignmentSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResponseAssignmentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            CategorySeeder::class,
            AssetSeeder::class,
            AssignmentSeeder::class,
        ]);
    }

    public function test_not_logged_user()
    {
        $id = 32; //staff_id: 13, state: 0
        $body = [
            'response' => true,
        ];
        $this->json(
            "POST",
            "api/response-assignment/$id",
            $body,
        )->assertStatus(401);
    }

    public function test_not_existed_assignment()
    {
        $id = 20000; //not existed
        $response = $this->postJson('api/login', [
            'username' => 'tuandd',
            'password' => '12345',
        ]); //staff id: 13
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'response' => true,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "POST",
            "api/response-assignment/$id",
            $body,
            $header
        )->assertStatus(404);
    }

    public function test_response_to_others_assignment()
    {
        $id = 36; //staff_id: 46, state: 0
        $response = $this->postJson('api/login', [
            'username' => 'tuandd',
            'password' => '12345',
        ]); //staff id: 13
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'response' => true,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "POST",
            "api/response-assignment/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_response_to_respond_assignment()
    {
        $id = 35; //staff_id: 42, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'dungva',
            'password' => '12345',
        ]); //staff id: 42
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'response' => true,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "POST",
            "api/response-assignment/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_success_decline_response()
    {
        $id = 32; //staff_id: 13, state: 0
        $response = $this->postJson('api/login', [
            'username' => 'tuandd',
            'password' => '12345',
        ]); //staff id: 13
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'response' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "POST",
            "api/response-assignment/$id",
            $body,
            $header
        )->assertStatus(200);
    }

    public function test_success_response()
    {
        $id = 32; //staff_id: 13, state: 0
        $response = $this->postJson('api/login', [
            'username' => 'tuandd',
            'password' => '12345',
        ]); //staff id: 13
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'response' => true,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "POST",
            "api/response-assignment/$id",
            $body,
            $header
        )->assertStatus(200);
    }
}
