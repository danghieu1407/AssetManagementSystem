<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\CategorySeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreCategoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
    }
    public function test_is_not_admin()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => false,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "id" => "SS",
            "name" => "Sam sung",
        ];
        $this->json('POST', 'api/category', $body)->assertStatus(401);
    }
    public function test_create_success()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "id" => "SS",
            "name" => "Samsung",
        ];
        $this->json('POST', 'api/category', $body)->assertStatus(201);
    }
    public function test_id_unique()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "id" => "LP",
            "name" => "Samsung",
        ];
        $this->json('POST', 'api/category', $body)->assertStatus(500);
    }
    public function test_name_unique()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "id" => "OK",
            "name" => "Monitor",
        ];
        $this->json('POST', 'api/category', $body)->assertStatus(500);
    }
    public function test_name_must_be_string()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "id" => "AP",
            "name" => 123,
        ];
        $this->json('POST', 'api/category', $body)->assertStatus(400);
    }

}
