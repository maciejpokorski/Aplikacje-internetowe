<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Exceptions;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createSuperAdminAndAssignItToMaciekUser();
    }

    private function createSuperAdminAndAssignItToMaciekUser()
    {
        $permission = $this->getPermissiondAndCreateNewOneIfDoesNotExist("everything");
        $role = $this->getRoleAndCreateNewOneIfDoesNotExist("super_admin");

        $role->syncPermissions($permission);

        $maciek = App\User::where('email', "maciekpokorski@gmail.com")->first();
        if (!$maciek) {
            $maciek = UsersTableSeeder::staticUserInsert("Maciek", "maciekpokorski@gmail.com", "maciek");
        }
        $maciek->syncRoles('super_admin');
    }

    public static function getPermissiondAndCreateNewOneIfDoesNotExist($name){
         try {
            $permission = Permission::findByName($name);
        } catch (Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
            echo("Permission ".$name." has been created ");
            $permission = Permission::create(['name' => $name]);
        }
        return $permission;
    }

     public static function getRoleAndCreateNewOneIfDoesNotExist($name){
        try {
            $role = Role::findByName($name);
        } catch (Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
             echo("Role ".$name." has been created ");
            $role = Role::create(['name' => $name]);
        }
        return $role;
    }
}
