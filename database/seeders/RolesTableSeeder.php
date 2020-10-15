<?php

declare(strict_types=1);



use Illuminate\Database\Seeder;
use App\Models\System\Role;
use App\Models\System\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::ROLES;

        foreach ($roles as $name) {
            if (!Role::where('name', $name)->exists()) {
                $label = ucwords($name);

                $role = new Role;
                $role->name = $name;
                $role->label = str_replace('_', ' ', $label);
                $role->status = Role::ACTIVE;
                $role->saveOrFail();
            }
        }
    }
}
