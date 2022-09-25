<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\AssignmentSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateAssignmentTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
        $this->seed(AssignmentSeeder::class);
    }

    public function test_is_not_admin()
    {
        $response = $this->postJson('api/login', [
            "username" => "tuandd",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            'POST', 'api/assignment'
        );
        $viewUser->assertStatus(400);
    }
    public function test_authorize()
    {
        $response = $this->postJson('api/login', [
            "username" => "ducna",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            "staff_id" => 1,
            "asset_id" => 1,
            "assigned_date" => "2022-09-09",
            'note' => "test"
        ];
        $this->postJson('api/assignment', $body, [
            'Authorization' => "Bearer $token"
        ])->assertStatus(401);
    }
     public function test_create_success()
     {
         $response = $this->postJson('api/login', [
             "username" => "huymg",
             "password" => "12345"
         ]);
         $response->assertStatus(200);
         $token = $response->getData()->token;
         $body = [
             "staff_id" => 1,
             "asset_id" => 1,
             "assigned_date" => "2022-09-09",
             'note' => "test"
         ];
         $this->postJson('api/assignment', $body, [
             'Authorization' => "Bearer $token"
         ])->assertStatus(201);
     }
    public function test_staff_not_exits()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "staff_id" => 100,
            "asset_id" => 20,
            "assigned_date" => "2022-08-08",
            'note' => "test"
        ];
        $this->json('POST', 'api/assignment', $body)->assertStatus(400);
    }
    public function test_asset_not_exits()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "staff_id" => 10,
            "asset_id" => 200,
            "assigned_date" => "2022-08-08",
            'note' => "test"
        ];
        $this->json('POST', 'api/assignment', $body)->assertStatus(400);
    }
    public function test_assigned_date_not_exits()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "staff_id" => 10,
            "asset_id" => 200,
            "assigned_date" => "",
            'note' => "test"
        ];
        $this->json('POST', 'api/assignment', $body)->assertStatus(400);
    }
    public function test_note_not_exits()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "staff_id" => 10,
            "asset_id" => 200,
            "assigned_date" => "2022-08-08",
            'note' => ""
        ];
        $this->json('POST', 'api/assignment', $body)->assertStatus(400);
    }
}
