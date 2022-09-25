<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AssetSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AssignmentSeeder;

class ManageReportTest extends TestCase
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
    public function test_sort_by_category_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByCategory=asc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_category_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByCategory=desc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_total_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByTotal=asc"
        );
        $viewReport->assertStatus(200);
    }
    
     public function test_sort_by_total_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByTotal=desc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_assigned_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByAssigned=asc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_assigned_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByAssigned=desc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_available_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByAvailable=asc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_available_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByAvailable=desc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_not_available_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByNotAvailable=asc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_not_available_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByNotAvailable=desc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_waiting_for_recycling_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByWaitingForRecycling=asc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_waiting_for_recycling_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByWaitingForRecycling=desc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_recycled_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByRecycled=asc"
        );
        $viewReport->assertStatus(200);
    }

    public function test_sort_by_recycled_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewReport = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewReport = $this->json(
            "GET",
            "/api/report?page=1&sortByRecycled=desc"
        );
        $viewReport->assertStatus(200);
    }
}
