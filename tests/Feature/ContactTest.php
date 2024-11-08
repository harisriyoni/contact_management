<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;
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
    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->get("/api/contacts/$contact->id", [
            'Authorization' => 'bayi',
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'bayi',
                    'last_name' => 'bayi',
                    'email' => 'bayi@gmail.com',
                    'phone' => '085644587923',
                ]
            ]);
    }
    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->get('/api/contacts/' . ($contact->id + 1), [
            'Authorization' => 'bayi',
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }
    public function testGetOtherContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->get("/api/contacts/$contact->id", [
            'Authorization' => 'bayi2',
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }
    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->put('/api/contacts/' . $contact->id, [
            'first_name' => 'bayi2',
            'last_name' => 'bayi2',
            'email' => 'bayi2@gmail.com',
            'phone' => '085645892145',
        ], [
            'Authorization' => 'bayi'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'bayi2',
                    'last_name' => 'bayi2',
                    'email' => 'bayi2@gmail.com',
                    'phone' => '085645892145',
                ]
            ]);
    }
    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->put('/api/contacts/' . $contact->id, [
            'first_name' => '',
            'last_name' => 'bayi2',
            'email' => 'bayi2@gmail.com',
            'phone' => '085645892145',
        ], [
            'Authorization' => 'bayi'
        ])->assertStatus(400)
            ->assertJson([
                "errors" => [
                    'first_name' => [
                        'The first name field is required.'
                    ]
                ]
            ]);
    }
    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->delete("/api/contacts/$contact->id", [], ['Authorization' => 'bayi',])->assertStatus(200)
            ->assertJson([
                'data' => true,
            ]);
    }
    public function testDeleteFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();
        $this->delete('/api/contacts/' . ($contact->id + 1), [], ['Authorization' => 'bayi',])->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['not found']
                ]
            ]);
    }
    public function testSearchByFirstName()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts/?name=Gita', [
            'Authorization' => 'bayi',
        ])->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }
    public function testSearchByLastName() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts/?name=last', [
            'Authorization' => 'bayi',
        ])->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchByEmail() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts/?email=bayi', [
            'Authorization' => 'bayi',
        ])->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }
    public function testSearchByPhone() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts/?phone=085', [
            'Authorization' => 'bayi',
        ])->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(10, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
    }

    public function testSearchNotfound() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts/?phone=ksoong', [
            'Authorization' => 'bayi',
        ])->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(0, count($response['data']));
        self::assertEquals(0, $response['meta']['total']);
    }
    public function testSearchWithPage() {
        $this->seed([UserSeeder::class, SearchSeeder::class]);
        $response = $this->get('/api/contacts/?size=5&page=2', [
            'Authorization' => 'bayi',
        ])->assertStatus(200)
            ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));
        self::assertEquals(5, count($response['data']));
        self::assertEquals(20, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }
}
