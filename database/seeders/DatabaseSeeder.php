<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'email' => 'admin@admin.com',
        ];

        if (! User::query()->where($data)->first()) {
            $admin = new User(
                array_merge($data, [
                    'password' => bcrypt('password'),
                    'last_name' => 'Administrator',
                    'role' => 'admin',
                    'premium_since' => Carbon::now(),
                ])
            );
            $admin->save();
        }
    }
}
