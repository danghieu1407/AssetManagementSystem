<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AssetSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AssignmentSeeder;

class ManageReturningTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(AssignmentSeeder::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_search_returning()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&search=fakedata&page=1"
        );
        $viewAssignment->assertStatus(500);
    }

    public function test_filter_by_state()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=1&page=1"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_filter_by_returned_date()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByDate=2022-08-03&filterByState=3&page=1"
        );
        $viewAssignment->assertStatus(200);
    }
   
    public function test_sort_by_no_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByNo=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_no_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByNo=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_asset_code_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAssetCode=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_asset_code_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAssetCode=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_asset_name_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAssetName=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_asset_name_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAssetName=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_requested_by_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByRequestedBy=asc"
        );
        $viewAssignment->assertStatus(200);
    }
    
    public function test_sort_by_requested_by_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByRequestedBy=desc"
        );
        $viewAssignment->assertStatus(200);
    }
    
    public function test_sort_by_assigned_date_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAssignedDate=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_assigned_date_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAssignedDate=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_accepted_by_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAcceptedBy=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_accepted_by_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByAcceptedBy=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_returned_date_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByReturnedDate=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function  test_sort_by_returned_date_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByReturnedDate=desc"
        );
        $viewAssignment->assertStatus(200);
    }
    
    public function  test_sort_by_state_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByState=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_state_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByState=desc"
        );
        $viewAssignment->assertStatus(200);
    }
    
    public function test_sort_by_edit_returning()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAssignment = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAssignment = $this->json(
            "GET",
            "/api/returning?filterByState=3&page=1&sortByEditReturning"
        );
        $viewAssignment->assertStatus(200);
    }

    
}
