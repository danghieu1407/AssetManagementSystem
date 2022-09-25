<?php

namespace Tests\Feature;

use Database\Seeders\AssetSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AssignmentSeeder;
use Tests\TestCase;

class UserCreateReturningRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(AssetSeeder::class);
        $this->seed(AssignmentSeeder::class);


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
    public function test_success_user_create_returning_request()
    {
        
        $santumUser = $this->getSanctum("ducna", "12345");
        $userCreateReturning = $this->postJson('api/assignment/7/user-return', [], [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $userCreateReturning->assertStatus(201);
    }
    public function test_user_does_not_have_permission()
    {
        $santumUser = $this->getSanctum("ducna", "12345");
        $userCreateReturning = $this->postJson('api/assignment/9/user-return', [], [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $userCreateReturning->assertStatus(400);
    }


  
}
