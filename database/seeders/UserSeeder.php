<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['phone_number' => '09123123123'],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'password' => 'password',
            ]
        );
    }
}
