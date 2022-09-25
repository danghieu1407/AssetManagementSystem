<?php

namespace Tests\Feature;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Assignment;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ManageUserTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_staff_fail_to_manage_user()
    {
        $response = $this->postJson('api/login', [
            'username' => 'dungva',
            'password' => '12345',
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;

        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser->assertStatus(401);
    }

    public function test_admin_view_user_in_the_same_location_only()
    {
        $response = $this->postJson('api/login', [
            "username" => "tuandc",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;

        $viewUser = $this->getJson('api/manageUser', [
            'Authorization' => "Bearer $token"
        ]);
        $viewUser->assertStatus(200)->assertJsonFragment([
            "location" => "HCM"
        ])->assertJsonMissing([
            "location" => "HN"
        ])->assertJsonMissing([
            "location" => "DN"
        ]);
    }

    public function test_sort_by_staff_code_desc()
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
        $viewUser = $this->json("GET", "/api/manageUser?page=1&sortByStaffCode=desc");
        $viewUser->assertStatus(200);
        $viewUser->assertJsonStructure(["data"]);
    }

    public function test_sort_by_staff_code_asc()
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
        $viewUser = $this->json("GET", "/api/manageUser?page=1&sortByStaffCode=desc");
        $viewUser->assertStatus(200);
        $viewUser->assertJsonStructure(["data"]);
    }

    public function test_filter_by_admin()
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
        $viewUser = $this->json("GET", "/api/manageUser?filter=true&page=1");
        $viewUser->assertStatus(200);
    }

    public function test_search_by_user()
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
            "/api/manageUser?search=FakeData&page=1"
        );
        $viewUser->assertStatus(500);
    }

    public function test_sort_by_fullname_desc()
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
            "/api/manageUser?page=1&sortByFullName=desc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_fullname_asc()
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
            "/api/manageUser?page=1&sortByFullName=asc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_joined_date_asc()
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
            "/api/manageUser?page=1&sortByJoinedDate=asc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_joined_date_desc()
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
            "/api/manageUser?page=1&sortByJoinedDate=desc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_type_asc()
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
            "/api/manageUser?page=1&sortByType=asc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_type_desc()
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
            "/api/manageUser?page=1&sortByType=desc"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_edit_user_desc()
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
            "/api/manageUser?page=1&sortByEditUser"
        );
        $viewUser->assertStatus(200);
    }

    public function test_sort_by_create_user_desc()
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
            "/api/manageUser?page=1&sortByCreateUser"
        );
        $viewUser->assertStatus(200);
    }
    public function test_can_not_disable_user(){
        $response = $this->getJson('api/can-disable/2');
        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json
//                ->where('message', 'Assignment available')
//                ->where('disable', false)
//                ->etc();
//        });
    }
    public function test_can_disable_user(){
//        $this->postJson('api/login', [
//            "username" => "huymg",
//            "password" => "12345"
//        ]);
//        $this->postJson('api/asset', [
//            "name" => "huymg",
//            "category_id" => "LP",
//            "installed_date" => "07-06-2000",
//            "state" => "0",
//            "specification" => "specification"
//        ]);
//        $this->postJson('api/assignment', [
//            "staff_id" => "huymg",
//            "asset_id" => "1",
//            "assigned_date" => "2022-09-09",
//            "note" => ""
//        ]);
        $response = $this->getJson('api/can-disable/1');
        $response->assertStatus(200);
    }
    public function test_disable_user(){
        $response = $this->postJson('api/login', [
            "username" => "huymg",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $data = $this->getJson('api/disable/1',[
            'Authorization' => "Bearer $token"
         ]);
        $data->assertStatus(200);
    }
    public function test_authorize(){
        $response = $this->postJson('api/login', [
            "username" => "ducna",
            "password" => "12345"
        ]);
        $response->assertStatus(200);
        $token = $response->getData()->token;
        $data = $this->getJson('api/disable/1',[
            'Authorization' => "Bearer $token"
        ]);
        $data->assertStatus(401);
    }
}
