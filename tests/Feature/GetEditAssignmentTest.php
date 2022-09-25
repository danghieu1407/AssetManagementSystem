<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\AssignmentSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetEditAssignmentTest extends TestCase
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

    public function test_unlogged_user()
    {
        $id = 10;//state: 0, location: DN
        $this->json(
            "GET",
            "api/assignment/$id/edit",
            [],
        )->assertStatus(401);
    }

    public function test_not_an_admin_user()
    {
        $id = 10;//state: 0, location: DN
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
            "api/assignment/$id/edit",
            [],
            $header
        )->assertStatus(401);
    }

    public function test_not_existed_assignment()
    {
        $id = 20000; //un-existed assignment id
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
            "api/assignment/$id/edit",
            [],
            $header
        )->assertStatus(404);
    }

    public function test_assignment_is_not_available()
    {
        $id = 9; //location: DN, state: 1
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
            "api/assignment/$id/edit",
            [],
            $header
        )->assertStatus(422);
    }

    public function test_success_get_edit_assignment()
    {
        $id = 10;//state: 0, location: DN
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
            "api/assignment/$id/edit",
            [],
            $header
        )->assertStatus(200);
    }
}
