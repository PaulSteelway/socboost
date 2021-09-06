<?php
/**
 * Copyright. "Hyipium" engine. All rights reserved.
 * Any questions? Please, visit https://hyipium.com
 */

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Class RolesAndPermissionsSeeder
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        if (Role::where('name', 'root')->count() == 0) {
            Role::create(['name' => 'root']);
            echo "Role 'root' registered.\n";
        } else {
            echo "Role 'root' already registered.\n";
        }

        if (Role::where('name', 'admin')->count() == 0) {
            Role::create(['name' => 'admin']);
            echo "Role 'admin' registered.\n";
        } else {
            echo "Role 'admin' already registered.\n";
        }
    }
}
