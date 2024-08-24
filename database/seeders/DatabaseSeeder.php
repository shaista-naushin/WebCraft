<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@webcraft.com',
            'password' => Hash::make('admin'),
            'activated' => 1,
            'email_verified_at' => Carbon::now(),
            'avatar' => '/assets/img/default-avatar.jpg',
            'role' => 'admin',
        ]);
    }
}
