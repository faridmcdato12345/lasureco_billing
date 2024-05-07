<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed the default permissions
        $permissions = Permission::defaultPermissions();

        foreach ($permissions as $perms) {
            Permission::firstOrCreate(['name' => $perms]);
        }

        $this->command->info('Default Permissions added.');

        // Confirm roles needed
        if ($this->command->confirm('Create Roles for user, default is admin? [y|N]', true)) {

            // Ask for roles from input
            $input_roles = $this->command->ask('Enter roles.', 'Admin');
            $role = Role::firstOrCreate(['name' => $input_roles]);

            if( $role->name == 'Admin' ) {
                // assign all permissions
                $role->syncPermissions(Permission::all());
                $this->command->info('Admin granted all the permissions');
            } 
            // create one user for each role
            $this->createUser($role);
            $this->command->info('Roles ' . $input_roles . ' added successfully');
        }
        
    }
    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($role)
    {
        $user = \App\Models\User::factory()->count(1)->create();
        $u = User::latest()->get();
        $this->command->warn($u);
        $user[0]->assignRole($role->name);

        if( $role->name == 'Admin' ) {
            $this->command->info('Here is your admin details to login:');
            $this->command->warn($u[0]->username);
            $this->command->warn('Password is "admin123"');
        }
    }
}