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
        User::factory(5)->create();

        User::create([
            'name' => 'Sihab Admin',
            'username' => 'admin',
            'email' => 'admin@cbt',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'kelas' => 'XII RPL',
        ]);


        $this->call(QuestionSeeder50::class);
    }
}
