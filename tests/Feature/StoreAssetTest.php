<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreAssetTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
    }
     public function test_is_not_admin()
     {
         $response = $this->postJson('api/login', [
             "username" => "ducna",
             "password" => "12345"
         ]);
         $response->assertStatus(200);
         $token = $response->getData()->token;
         $body = [
             "name" => "Mouse",
             "category_id" => "LP",
             "installed_date" => "2000-07-06",
             "state" => 0,
             "specification" => "Non adipisci non quas quas.",
         ];
         $this->json('POST', 'api/asset', $body, ['Authorization' => "Bearer $token"])->assertStatus(401);
     }
    public function test_create_asset_success()
    {
        $response = $this->postJson('api/login', [
            "username" => "huymg",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
         $body = [
             "name" => "Mouse",
             "category_id" => "LP",
             "installed_date" => "2000-07-06",
             "state" => 0,
             "specification" => "Non adipisci non quas quas.",
         ];
        $this->postJson('api/asset', $body, [
            'Authorization' => "Bearer $token"
        ])->assertStatus(201);
    }
}
