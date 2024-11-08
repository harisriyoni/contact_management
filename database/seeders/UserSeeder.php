<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'bayi',
            'password' =>  Hash::make('bayi'),
            'name' =>  'bayi',
            'token' => 'bayi'
        ]);
        User::create([
            'username' => 'bayi2',
            'password' =>  Hash::make('bayi2'),
            'name' =>  'bayi2',
            'token' => 'bayi2'
        ]);
    }
}
