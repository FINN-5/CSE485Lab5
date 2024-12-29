<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'J97',
                'email' => 'uoa@gmail.com',
                'password' => '123456',
            ],
        ];
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
