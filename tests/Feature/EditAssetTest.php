<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AssetSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditAssetTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            CategorySeeder::class,
            AssetSeeder::class,
        ]);
    }

    public function test_not_logged_in_user(): void
    {
        $id = 2; //location: DN, state: -1
        $body = [
            'name' => 'Asset 1',
            'specification' => 'Specification 1',
            'installed_date' => '2020-01-01',
            'state' => '1',
        ];
        $this->json(
            "PUT",
            "api/asset/$id",
            $body,
        )->assertStatus(401);
    }

    public function test_not_admin(): void
    {
        $id = 2; //location: DN, state: -1
        $response = $this->postJson('api/login', [
            'username' => 'tuanpa',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'name' => 'Asset 1',
            'specification' => 'Specification 1',
            'installed_date' => '2020-01-01',
            'state' => '1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/asset/$id",
            $body,
            $header
        )->assertStatus(401);
    }

    public function test_unfilled_form(): void
    {
        $id = 2; //location: DN, state: -1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'name' => 'Asset 1',
            'specification' => 'Specification 1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/asset/$id",
            $body,
            $header
        )->assertStatus(400);
    }

    public function test_wrong_value_form(): void
    {
        $id = 2; //location: DN, state: -1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'name' => 'Asset 1',
            'specification' => 'Specification 1',
            'installed_date' => '2020-01-01',
            'state' => '2',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/asset/$id",
            $body,
            $header
        )->assertStatus(400);
    }

    public function test_not_existed_asset(): void
    {
        $id = 20000; //not existed
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'name' => 'Asset 1',
            'specification' => 'Specification 1',
            'installed_date' => '2020-01-01',
            'state' => '1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/asset/$id",
            $body,
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
        $body = [
            'name' => 'Asset 1',
            'specification' => 'Specification 1',
            'installed_date' => '2020-01-01',
            'state' => '1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/asset/$id",
            $body,
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
         $body = [
             'name' => 'Asset 1',
             'specification' => 'Specification 1',
             'installed_date' => '2020-01-01',
             'state' => '1',
         ];
         $header = [
             'Authorization' => "Bearer $token"
         ];
         $this->json(
             "PUT",
             "api/asset/$id",
             $body,
             $header
         )->assertStatus(422);
     }

    public function test_success_edit(): void
    {
        $id = 2; //location: DN, state: -1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'name' => 'Asset 1',
            'specification' => 'Specification 1',
            'installed_date' => '2020-01-01',
            'state' => '1',
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/asset/$id",
            $body,
            $header
        )->assertStatus(200);
    }
}
