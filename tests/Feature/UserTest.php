<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use function PHPUnit\Framework\assertJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'harisriyoni',
            'password' => 'password',
            'name' => 'haris',
        ])
            ->assertStatus(201)
            ->assertJson([
                "data" => [
                    'username' => 'harisriyoni',
                    'name' => 'haris',
                ],
            ]);
    }
    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => '',
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => [
                        'The username field is required.',
                    ],
                    'password' => [
                        'The password field is required.',
                    ],
                    'name' => [
                        'The name field is required.',
                    ],
                ],
            ]);
    }
    public function testRegisterAlreadyExist()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'harisriyoni',
            'password' => 'password',
            'name' => 'haris',
        ])
            ->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'username' => 'username already registered',
                ],
            ]);
    }
    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $user = User::query()->first();
        $this->post('/api/users/login', [
            'username' => 'bayi',
            'password' => 'bayi',
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'name',
                    'token',
                ],
            ]);
        $user = User::where('username', 'bayi')->first();
        self::assertNotNull($user->token);
    }
    public function testLoginUsernameNotFound()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'bayi1',
            'password' => 'bayi',
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    'message' => 'username or password wrong',
                ],
            ]);
    }
    public function testLoginPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'bayi',
            'password' => 'bayi1',
        ])
            ->assertStatus(401)
            ->assertJson([
                "errors" => [
                    'message' => 'username or password wrong',
                ],
            ]);
    }
}
