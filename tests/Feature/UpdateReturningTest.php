<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Returning;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\AssetSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AssignmentSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ReturningSeeder;
use Illuminate\Http\Response;

class UpdateReturningTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
        $this->seed(AssignmentSeeder::class);
        $this->seed(ReturningSeeder::class);
    }

    public function get_Sanctum($username, $password)
    {
        $response = $this->postJson('api/login', [
            'username' => $username,
            'password' => $password,
        ]);
        $response->assertStatus(200);
        return $response->getData();
    }

    public function test_check_is_not_admin()
    {
        $santumUser = $this->get_Sanctum("ducna", "12345");
        $returningId = 26;

        $response = $this->json('put', "api/returning/{$returningId}?state=1",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertUnauthorized();
    }

    public function test_get_returning_by_id()
    {
        $santumUser = $this->get_Sanctum("bichvht", "12345");
        $returningId = 1;

        $response = $this->json('get', "api/returning/{$returningId}",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertOk(); // 200
    }

    public function test_get_returning_by_id_which_account_is_not_admin()
    {
        $santumUser = $this->get_Sanctum("ducna", "12345");
        $returningId = 1;

        $response = $this->json('get', "api/returning/{$returningId}",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertUnauthorized(); // 401
    }

    public function test_update_returning_successful()
    {
        $santumUser = $this->get_Sanctum("bichvht", "12345");
        $returningId = 1;

        $response = $this->json('put', "api/returning/{$returningId}?state=1",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertOk(); // 200
    }

    public function test_returning_already_completed()
    {
        $santumUser = $this->get_Sanctum("bichvht", "12345");
        $returningId = 24;

        $response = $this->json('put', "api/returning/{$returningId}?state=1",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertUnprocessable(); // 422
    }

    public function test_admin_is_not_in_the_same_location_with_returning()
    {
        $santumUser = $this->get_Sanctum("diepntn", "12345");
        $returningId = 20;

        $response = $this->json('put', "api/returning/{$returningId}?state=aaaa",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertStatus(401);
    }

    public function test_update_returning_with_invalid_state()
    {
        $santumUser = $this->get_Sanctum("bichvht", "12345");
        $returningId = 1;

        $response = $this->json('put', "api/returning/{$returningId}?state=0",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ])
        ->assertUnprocessable(); //422
        $response->dump();
    }

    public function test_returning_is_not_exist()
    {
        $santumUser = $this->get_Sanctum("diepntn", "12345");
        $returningId = 200;
        $this->json('put', "api/returning/{$returningId}?state=1",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ])
        ->assertNotFound(); // 404
    }

    public function test_accepted_by_is_not_exist()
    {
        $santumUser = $this->get_Sanctum("diepntn", "12345");
        $santumUserId = 2;

        // create new assignment
        $body = [
            "staff_id" => $santumUserId,
            "asset_id" => 1,
            "assigned_date" => "2022-09-09",
            'note' => "test"
        ];
        $this->postJson('api/assignment', $body, [
            'Authorization' => "Bearer $santumUser->token"
        ])
        ->assertCreated(); //201

        // accept response
        $body = [
            'response' => true,
        ];
        $this->postJson("api/response-assignment/41",$body, [
            'Authorization' => "Bearer $santumUser->token"
        ])->assertOk(); // 200

        // create new returning
        $this->postJson("api/assignment/41/user-return", [], [
            'Authorization' => 'Bearer'.$santumUser->token
        ])
        ->assertStatus(201);

        //  complete returning
        $this->json('put', "api/returning/41?state=1",[], [
            'Authorization' => 'Bearer ' . $santumUser->token
        ])
        ->assertOk(); // 200
    }

}
