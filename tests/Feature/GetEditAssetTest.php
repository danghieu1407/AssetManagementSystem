<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetEditAssetTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
    }

    public function test_not_logged_in_user(): void
    {
        $id = 2; //location: DN, state: -1
        $this->json(
            "GET",
            "api/asset/$id/edit",
            []
        )->assertStatus(401);
    }

    public function test_not_admin(): void
    {
        $id = 2; //location: DN, state: -1
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
            "api/asset/$id/edit",
            [],
            $header
        )->assertStatus(401);
    }

    public function test_not_existed_asset(): void
    {
        $id = 20000; //not existed asset
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
            "api/asset/$id/edit",
            [],
            $header
        )->assertStatus(404);
    }

    public function test_asset_in_other_location(): void
    {
        $id = 47; //location: HCM, state: 1
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
            "api/asset/$id/edit",
            [],
            $header
        )->assertStatus(401);
    }

    public function test_assigned_asset(): void
    {
        $id = 36; //location: DN, state: 2
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
            "api/asset/$id/edit",
            [],
            $header
        )->assertStatus(422);
    }

    public function test_success_get_edit(): void
    {
        $id = 2; //location: DN, state: -1
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
            "api/asset/$id/edit",
            [],
            $header
        )->assertStatus(200);
    }
}
