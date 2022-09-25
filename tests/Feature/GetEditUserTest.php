<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetEditUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_not_logged_in_admin()
    {
        $id = 39; //location: DN, state: 1
        $this->json(
            "GET",
            "api/user/$id/edit",
            [],
        )->assertStatus(401);
    }

    public function test_user_is_not_an_admin()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'tuanpa',
            'password' => '12345',
        ]); //admin: 0, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "GET",
            "api/user/$id/edit",
            [],
            $header
        )->assertStatus(401);
    }

    public function test_user_is_not_existed()
    {
        $id = 20000; //not existed user
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "GET",
            "api/user/$id/edit",
            [],
            $header
        )->assertStatus(404);
    }

    public function test_not_same_location_user()
    {
        $id = 3; //location: HCM
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "GET",
            "api/user/$id/edit",
            [],
            $header
        )->assertStatus(401);
    }

    public function test_get_user_edit_success()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "GET",
            "api/user/$id/edit",
            [],
            $header
        )->assertStatus(200);
    }
}
