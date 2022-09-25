<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetCategoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
    }
    public function test_is_admin()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => false,
        ]));
        $this->json('GET', "api/category")
            ->assertStatus(401);
    }
    public function test_get_category_success()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
        ]));
        $this->json('GET', "api/category")
            ->assertStatus(200);
    }

}
