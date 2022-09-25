<?php

namespace Tests\Feature;

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

class DeleteReturningTest extends TestCase
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

        $response = $this->json('delete', "api/returning/{$returningId}",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertUnauthorized();
    }

    public function test_delete_returning_successful()
    {
        $santumUser = $this->get_Sanctum("bichvht", "12345");
        $returningId = 1;

        $response = $this->json('delete', "api/returning/{$returningId}",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertOk(); // 200
    }

    public function test_returning_already_completed()
    {
        $santumUser = $this->get_Sanctum("bichvht", "12345");
        $returningId = 4;

        $response = $this->json('delete', "api/returning/{$returningId}",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertUnprocessable(); // 422
    }

    public function test_admin_is_not_in_the_same_location_with_returning()
    {
        $santumUser = $this->get_Sanctum("diepntn", "12345");
        $returningId = 20;

        $response = $this->json('delete', "api/returning/{$returningId}",[], [
            'Authorization' => 'Bearer ' . $santumUser->token,
        ]);
        $response->assertStatus(401);
    }

    public function test_returning_is_not_exist()
    {
        $santumUser = $this->get_Sanctum("diepntn", "12345");
        $returningId = 200;
        $this->json('delete', "api/returning/{$returningId}",[], [
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
        $response =  $this->postJson("api/response-assignment/41",$body, [
            'Authorization' => "Bearer $santumUser->token"
        ])->assertOk(); // 200

        // create new returning
        $this->postJson("api/assignment/41/user-return", [], [
            'Authorization' => 'Bearer'.$santumUser->token
        ])
        ->assertStatus(201);

        // delete returning
        $this->json('delete', "api/returning/41",[], [
            'Authorization' => 'Bearer ' . $santumUser->token
        ])
        ->assertOk(); // 200
    }

}
