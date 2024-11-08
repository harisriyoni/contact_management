<?php

namespace Tests\Feature;

use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post(
            '/api/contacts',
            [
                'first_name' => 'haris',
                'last_name' => 'riyoni',
                'email' => 'haris@gmail.com',
                'phone' => '0856860149255',
            ],
            [
                'Authorization' => 'bayi'
            ]
        )->assertStatus(201)
            ->assertJson([
                "data" => [
                    'first_name' => 'haris',
                    'last_name' => 'riyoni',
                    'email' => 'haris@gmail.com',
                    'phone' => '0856860149255',
                ]
            ]);
    }
    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class]);
        $this->post(
            '/api/contacts',
            [
                'first_name' => '',
                'last_name' => 'riyoni',
                'email' => 'haris',
                'phone' => '0856860149255',
            ],
            [
                'Authorization' => 'bayi'
            ]
        )->assertStatus(400)
            ->assertJson([
                "errors" => [
                    "first_name" => [
                        "The first name field is required."
                    ],
                    "email" => [
                        "The email field must be a valid email address."
                    ]
                ]
            ]);
    }
}
