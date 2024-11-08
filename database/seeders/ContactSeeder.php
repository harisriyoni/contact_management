<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'bayi')->first();
        Contact::create([
            'first_name' => 'bayi',
            'last_name' => 'bayi',
            'email' => 'bayi@gmail.com',
            'phone' => '085644587923',
            'user_id' => $user->id,
        ]);
    }
}
