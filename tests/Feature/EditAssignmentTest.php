<?php

namespace Tests\Feature;

use Database\Seeders\AssetSeeder;
use Database\Seeders\AssignmentSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class EditAssignmentTest extends TestCase
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
        $id = 10;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body
        )->assertStatus(401);
    }

    public function test_not_an_admin()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'tuanpa',
            'password' => '12345',
        ]); //admin: 0 location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(401);
    }

    public function test_not_existed_assignment()
    {
        $id = 20000; //not existed
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(404);
    }

    public function test_not_same_location()
    {
        $id = 2; //location: HCM, state: 0
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(401);
    }

    public function test_unavailable_assignment()
    {
        $id = 9; //location: DN, state: -1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_not_existed_user()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 20000, //un-existed user
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(404);
    }

    public function test_not_existed_asset()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 20000, //un-existed asset
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(404);
    }

    public function test_assign_to_user_in_other_location()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 20, //state: 1, location: HCM
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(400);
    }

    public function test_assign_to_asset_in_other_location()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 33, //state: available, location: HCM
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(400);
    }

    public function test_assign_to_unavailable_asset()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 40, //state: un-available, location: DN
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(400);
    }

    public function test_assign_to_disable_user()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 26, //state: -1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(400);
    }

    public function test_success_update_assignment()
    {
        $id = 10;
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'asset_id' => 27, //state: available, location: DN
            'staff_id' => 25, //state: 1, location: DN
            'assigned_date' => '2020-01-01',
            'note' => 'Note 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "/api/assignment/$id",
            $body,
            $header
        )->assertStatus(200);
    }
}
