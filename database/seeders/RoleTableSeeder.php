<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = "admin";
        $role->description = "Admin User, all permission";
        $role->save();

        $role = new Role();
        $role->name = "author";
        $role->description = "Author User, edit permission";
        $role->save();

        $role = new Role();
        $role->name = "user";
        $role->description = "Normal User, just view";
        $role->save();
    }
}
