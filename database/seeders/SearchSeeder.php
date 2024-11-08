<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('username', 'bayi')->first();
        for ($i = 0; $i < 20; $i++) {
            Contact::create([
                'first_name' => 'Gita',
                'last_name' => 'last' . $i,
                'email' => 'bayi' . $i . '@gmail.com',
                'phone' => '08564458792' . $i,
                'user_id' => $user->id,
            ]);

}
}
}
