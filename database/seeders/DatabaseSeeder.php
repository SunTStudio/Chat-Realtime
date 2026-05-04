<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // 2 user akun dengan nama admin dan user passwordnya sama dengan nama akunnya
        // User::factory()->create([
        //     'name'     => 'admin',
        //     'email'    => 'admin@gmail.com',
        //     'password' => 'admin',
        // ]);     
        // User::factory()->create([
        //     'name'     => 'user',
        //     'email'    => 'user@gmail.com',
        //     'password' => 'user',
        // ]);
        User::factory()->create([
            'name'     => 'pengguna',
            'email'    => 'pengguna@gmail.com',
            'password' => 'pengguna',
        ]);
    }
}
