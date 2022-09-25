<?php

namespace Tests\Feature;

use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AssetSeeder;
use Database\Seeders\UserSeeder;

class ManageAssetTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(AssetSeeder::class);
        $this->seed(UserSeeder::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_staff_could_not_access()
    {
        $santumUser = $this->getSanctum("tuandd", "12345");

        $viewAsset = $this->getJson('api/asset', [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $viewAsset->assertStatus(401);
    }

    public function test_admin_can_only_manage_asset_in_the_same_location()
    {
        $santumUser = $this->getSanctum("quytc", "12345");

        $viewAsset = $this->getJson('api/asset', [
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

    public function test_admin_can_view_asset_detail()
    {
        $santumUser = $this->getSanctum("bichvht", "12345");

        $viewAsset = $this->getJson('api/asset/5', [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $viewAsset->assertStatus(200)->assertJsonFragment([
            "id" => 5
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

        
    public function test_filter_by_state()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAsset = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAsset = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=1,0,2&page=1"
        );
        $viewAsset->assertStatus(200);
    }

    public function test_filter_by_category()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewAsset = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewAsset = $this->json(
            "GET",
            "/api/asset?filterByCategory=LP&filterByState=1,0,2&page=1"
        );
        $viewAsset->assertStatus(200);
    }

    public function test_search_by_asset()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&search=fakedata&page=1"
        );
        $viewUser->assertStatus(500);
    }

    public function test_sort_by_asset_code_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByAssetCode=asc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_asset_name_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByName=asc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_asset_code_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByName=desc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_category_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByCategory=asc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_category_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByCategory=desc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_state_asc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByState=asc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_state_desc()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByState=desc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_edit_asset()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByEditAsset"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_create_asset()
    {
        $response = $this->postJson('api/login', [
            "username" => "kienvv",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser = $this->json(
            "GET",
            "/api/asset?filterByCategory=3&filterByState=2,1,0&page=1&sortByCreateAsset"
        );
        $viewUser->assertStatus(200);
    }



}
