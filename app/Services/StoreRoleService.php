<?php

namespace App\Services;

use App\Models\Role;

class StoreRoleService {

    public function storeRole($role){
        if(!empty($role)) {
            $data = Role::create([
                'name' => $role,
                'guard_name' => 'web',
            ]);
            return $data;
        } else {
            return null;
        }
    }
}