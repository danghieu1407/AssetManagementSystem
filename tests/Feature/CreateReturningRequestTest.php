<?php

namespace Tests\Feature;

use Database\Seeders\AssetSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AssignmentSeeder;
use Tests\TestCase;

class CreateReturningRequestTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
        $this->seed(UserSeeder::class);
        $this->seed(AssetSeeder::class);
        $this->seed(AssignmentSeeder::class);
    }
    public function test_success_create_returning_request()
    {
        
        $santumUser = $this->getSanctum("huymg", "12345");
        $userCreateReturning = $this->postJson('api/assignment/9/return', [], [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $userCreateReturning->assertStatus(201);
    }
     public function test_invalid_assignment()
    {
        $santumUser = $this->getSanctum("huymg", "12345");
        $userCreateReturning = $this->postJson('api/assignment/10/return', [], [
            'Authorization' => "Bearer $santumUser->token"
        ]);
        $userCreateReturning->assertStatus(200);
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
}
