<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['pemilik','admin'];

        foreach ($roles as $role) {
            Role::create([
                'role_name' => $role
            ]);
        }
    }
}
