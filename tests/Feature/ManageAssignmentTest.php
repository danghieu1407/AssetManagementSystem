<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AssetSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AssignmentSeeder;

class ManageAssignmentTest extends TestCase
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
    public function test_staff_could_not_manage_assginment()
    {
        $santumUser = $this->getSanctum("tuandd", "12345");

        $viewAsset = $this->getJson('api/assignment', [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $viewAsset->assertStatus(401);
    }

    public function test_admin_can_manage_assignment_in_the_same_location_only()
    {
        $santumUser = $this->getSanctum("quytc", "12345");

        $viewAsset = $this->getJson('api/assignment', [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $viewAsset->assertStatus(200)->assertJsonFragment([
            "location" => "HN"
        ])->assertJsonMissing([
            "location" => "HCM"
        ])->assertJsonMissing([
            "location" => "DN"
        ]);
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

    public function test_search_assignment()
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
            "/api/assignment?filterByStateManage=3&search=Fakedata&page=1"
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
            "/api/assignment?filterByStateManage=1&page=1"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_filter_by_assigned_date()
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
            "/api/assignment?filterByDate=2022-05-23&filterByStateManage=3&page=1&sortByAssignedDate=desc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByNo=asc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByNo=desc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssetCode=asc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssetCode=desc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssetName=asc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssetName=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_assigned_to_asc()
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssignedTo=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_assigned_to_desc()
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssignedTo=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_assigned_by_asc()
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssignedBy=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_assigned_by_desc()
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssignedBy=desc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssignedDate=asc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByAssignedDate=desc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_state_asc()
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
            "/api/assignment?filterByStateManage=3&page=1&sortByState=asc"
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
            "/api/assignment?filterByStateManage=3&page=1&sortByState=asc"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_sort_by_edit_assigment()
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
            "/api/assignment?filterByStateManage=3&page=1&sortByEditAssignment"
        );
        $viewAssignment->assertStatus(200);
    }
    public function test_sort_by_create_assigment()
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
            "/api/assignment?filterByStateManage=3&page=1&sortByCreateAssignment"
        );
        $viewAssignment->assertStatus(200);
    }

    public function test_filter_by_state_home(){
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
            "/api/view-assignment?filterByStateHome=3&page=1"
        );
        $viewAssignment->assertStatus(200);
    }
}
