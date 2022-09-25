<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use withFaker;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }
    public function getSanctum($username, $password)
    {
        $response = $this->postJson('api/login', [
            'username' => $username,
            'password' => $password,
        ]);
        $response->assertStatus(200);
        return $response->getData();
    }
    public function test_is_not_admin()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => false,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "first_name" => "huynh",
            "last_name" => "nguyen",
            "date_of_birth" => "31-12-2000",
            "joined_date" => "11-06-2001",
            "admin" => 1,
            "gender" => 1
        ];
        $this->json('POST', 'api/user/store', $body)->assertStatus(401);
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
             "first_name" => "huynh",
             "last_name" => "nguyen",
             "date_of_birth" => "31-12-2000",
             "joined_date" => "11-06-2001",
             "admin" => 1,
             "gender" => 1,
             "location" => ""
         ];
         $userCreateReturning = $this->postJson('api/user/store', $body, [
             'Authorization' => "Bearer $token"
         ]);
         $userCreateReturning->assertStatus(201);
     }
     public function test_duplicate_name(){
         $response = $this->postJson('api/login', [
             "username" => "huymg",
             "password" => "12345"
         ]);
         $response->assertStatus(200);
         $token = $response->getData()->token;
         $body = [
             "first_name" => "huynh",
             "last_name" => "nguyen",
             "date_of_birth" => "31-12-2000",
             "joined_date" => "11-06-2001",
             "admin" => 1,
             "gender" => 1,
             "location" => ""
         ];
         $this->postJson('api/user/store', $body, [
             'Authorization' => "Bearer $token"
         ]);
         $userCreateReturning = $this->postJson('api/user/store', $body, [
             'Authorization' => "Bearer $token"
         ]);
         $userCreateReturning->assertStatus(201);
     }
    public function test_without_first_name()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "last_name" => "nguyen",
            "date_of_birth" => "31-12-2000",
            "joined_date" => "11-06-2001",
            "admin" => 0,
            "gender" => 1
        ];
        $this->json('POST', "api/user/store", $body)
            ->assertStatus(400);
    }
    public function test_without_last_name()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "first_name" => "nguyen",
            "date_of_birth" => "31-12-2000",
            "joined_date" => "11-06-2001",
            "admin" => 0,
            "gender" => 1
        ];
        $this->json('POST', "api/user/store", $body)
            ->assertStatus(400);
    }
    public function test_name_over_64_character()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "first_name" => "Huynh",
            "last_name" => "Avenger Avenger Avenger Avenger
                 Avenger Avenger Avenger Avenger Avenger
                 Avenger Avenger Avenger Avenger
                 Avenger Avenger Avenger Avenger Avenger",
            "date_of_birth" => "31-12-2000",
            "joined_date" => "11-06-2001",
            "admin" => 0,
            "gender" => 1
        ];
        $this->json('POST', "api/user/store", $body)
            ->assertStatus(400);
    }
    public function test_name_is_string()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "first_name" => 01234,
            "last_name" => "Huynh",
            "date_of_birth" => "31-12-2000",
            "joined_date" => "11-06-2001",
            "admin" => 0,
            "gender" => 1
        ];
        $this->json('POST', "api/user/store", $body)
            ->assertStatus(400);
    }
    public function test_dob_is_date()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "first_name" => "Nguyen",
            "last_name" => "Huynh",
            "date_of_birth" => 0,
            "joined_date" => "11-06-2001",
            "admin" => 0,
            "gender" => 1
        ];
        $this->json('POST', "api/user/store", $body)
            ->assertStatus(400);
    }
    public function test_under_18()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => true,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "first_name" => "nguyen",
            "date_of_birth" => "31-12-2010",
            "joined_date" => "11-06-2001",
            "admin" => 0,
            "gender" => 1
        ];
        $this->json('POST', "api/user/store", $body)
            ->assertStatus(400);
    }
    public function test_joined_date_is_weekend()
    {
        Sanctum::actingAs(User::factory()->create([
            'admin' => false,
            'location' => 'HN',
            'staff_code' => 'SD2001',
        ]));
        $body = [
            "first_name" => "nguyen",
            "last_name" => "Huynh",
            "date_of_birth" => "31-12-2000",
            "joined_date" => "17-07-2022",
            "admin" => 0,
            "gender" => 1
        ];
        $this->json('POST', "api/user/store", $body)
            ->assertStatus(400);
    }
}
