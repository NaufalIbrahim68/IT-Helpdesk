<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        User::updateOrCreate(
            ['npk' => 'TEST001'],
            [
                'name' => 'Testing User',
                'department' => 'IT',
                'role' => 'user',
                'password' => bcrypt('avi1234!')
            ]
        );
    }
}
