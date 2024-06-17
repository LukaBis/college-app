<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view ',
            'create ',
            'update ',
            'delete ',
            'restore ',
            'force-delete ',
        ];

        $models = [
            'Course',
            'User',
        ];

        foreach ($permissions as $permission) {
            foreach ($models as $model) {
                Permission::create([
                    'name' => $permission.$model,
                ]);
            }
        }
    }
}
