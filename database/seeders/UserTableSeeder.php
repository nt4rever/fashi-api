<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee = Role::where('name', 'admin')->first();
        $employee = new User();
        $employee->name = 'Le Van Tan';
        $employee->email = 'levantanald@gmail.com';
        $employee->password = bcrypt('123456');
        $employee->save();
        $employee->roles()->attach($role_employee);
    }
}
