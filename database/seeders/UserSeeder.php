<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@example.fr',
                'role_id' => Role::query()->whereAlias('admin')->first()->id,
            ]);

        User::factory()
            ->create([
                'role_id' => Role::query()->whereAlias('user')->first()->id,
            ]);
    }
}
