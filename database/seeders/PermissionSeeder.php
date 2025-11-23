<?php

namespace Database\Seeders;

use App\Enums\AdminModuleEnum;
use App\Enums\VendorModuleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define modules and their respective guards
        $modules = [
            'admin' => AdminModuleEnum::cases(),
            'vendor' => VendorModuleEnum::cases(),
        ];

        foreach ($modules as $guardName => $moduleEnums) {
            $this->seedPermissions($moduleEnums, $guardName);
            $this->assignPermissionsToFirstRole($guardName);
        }
    }

    private function seedPermissions($moduleEnums, $guardName)
    {
        foreach ($moduleEnums as $case) {
            foreach ($case->permissions() as $permission) {
                Permission::updateOrCreate(
                    ['name' => $permission, 'guard_name' => $guardName],
                    ['guard_name' => $guardName, 'parent_name' => $case->name]);
            }
        }
    }

    private function assignPermissionsToFirstRole($guardName)
    {
        // Get the first role for this guard
        $role = Role::where('guard_name', $guardName)->first();

        if ($role) {
            $permissions = Permission::where('guard_name', $guardName)->get();
            $role->syncPermissions($permissions);
        }
    }

}
