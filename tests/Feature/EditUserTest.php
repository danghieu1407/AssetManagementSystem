<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }

    public function test_not_logged_in_admin()
    {
        $id = 39; //location: DN, state: 1
        $body = [
            'date_of_birth' => '1990-02-20',
            'joined_date' => '2022-07-07',
            'gender' => false,
            'admin' => false,
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
        )->assertStatus(401);
    }

    public function test_user_is_not_an_admin()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'tuanpa',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '1990-02-20',
            'joined_date' => '2022-07-07',
            'gender' => false,
            'admin' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(401);
    }

    public function test_unfilled_form()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_fail_validated_form()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '1990-02-20',
            'joined_date' => '2022-07-07',
            'gender' => false,
            'admin' => null,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_not_existed_user()
    {
        $id = 20000; //not existed user
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '1990-02-20',
            'joined_date' => '2022-07-07',
            'gender' => false,
            'admin' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(404);
    }

    public function test_not_same_location_user()
    {
        $id = 3; //location: HCM, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '1990-02-20',
            'joined_date' => '2022-07-07',
            'gender' => false,
            'admin' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(401);
    }

    public function test_under_18_user()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '2010-02-20',
            'joined_date' => '2022-07-07',
            'gender' => false,
            'admin' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_join_before_dob()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '2000-02-20',
            'joined_date' => '1999-07-09',
            'gender' => false,
            'admin' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_join_at_no_work_day_user()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '1990-02-20',
            'joined_date' => '2022-07-09', //Saturday
            'gender' => false,
            'admin' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(422);
    }

    public function test_success_update_user()
    {
        $id = 39; //location: DN, state: 1
        $response = $this->postJson('api/login', [
            'username' => 'kienvv',
            'password' => '12345',
        ]); //admin, location: DN, state: 1
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $body = [
            'date_of_birth' => '1990-02-20',
            'joined_date' => '2022-07-07',
            'gender' => false,
            'admin' => false,
        ];
        $header = [
            'Authorization' => "Bearer $token"
        ];
        $this->json(
            "PUT",
            "api/user/$id",
            $body,
            $header
        )->assertStatus(200);
    }
}
