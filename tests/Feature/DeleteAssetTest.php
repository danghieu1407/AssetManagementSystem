<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\AssignmentSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteAssetTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
        $this->seed(AssignmentSeeder::class);
    }
    public function test_is_admin(): void
    {
        $id = 2;
        Sanctum::actingAs(User::factory()->create([
            'admin' => false,
        ]));
        $this->json('DELETE', "api/asset/$id")
            ->assertStatus(401);
    }
    public function test_delete_success(): void
    {
        $response = $this->postJson('api/login', [
            "username" => "huymg",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $this->deleteJson('api/asset/2', [], [
            'Authorization' => "Bearer $token"
        ])->assertStatus(200);
    }
    public function test_cant_delete(): void
    {
        $response = $this->postJson('api/login', [
            "username" => "huymg",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $this->deleteJson('api/asset/1', [], [
            'Authorization' => "Bearer $token"
        ])->assertStatus(400);
    }
    public function test_assignment_valid(): void
    {
        $response = $this->postJson('api/login', [
            "username" => "huymg",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $this->getJson('api/asset/1/can-delete', [
            'Authorization' => "Bearer $token"
        ])->assertStatus(400);
    }
    public function test_assignment_unvalid(): void
    {
        $response = $this->postJson('api/login', [
            "username" => "huymg",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $this->getJson('api/asset/20/can-delete',[
            'Authorization' => "Bearer $token"
        ])->assertStatus(200);
    }
}
