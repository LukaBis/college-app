<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $courseAdmin = Role::create(['name' => 'Course Admin']);
        $student = Role::create(['name' => 'Student']);

        $student->givePermissionTo('view User');
        $student->givePermissionTo('update User');

        $courseAdmin->givePermissionTo('view Course');
        $courseAdmin->givePermissionTo('update Course');
        $courseAdmin->givePermissionTo('view User');
        $courseAdmin->givePermissionTo('update User');
        $courseAdmin->givePermissionTo('create User');
        $courseAdmin->givePermissionTo('delete User');
    }
}
