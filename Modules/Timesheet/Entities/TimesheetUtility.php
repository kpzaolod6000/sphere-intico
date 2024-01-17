<?php

namespace Modules\Timesheet\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimesheetUtility extends Model
{
    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $staff_permissions=[
            'timesheet manage',
            'timesheet create',
            'timesheet edit',
            'timesheet delete',
        ];

        if($role_id == Null)
        {

            // vender
            $roles_v = Role::where('name','staff')->get();

            foreach($roles_v as $role)
            {
                foreach($staff_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    $role->givePermissionTo($permission);
                }
            }   

        }
        else
        {
            if($rolename == 'staff')
            {
                $roles_v = Role::where('name','staff')->where('id',$role_id)->first();
                foreach($staff_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    $roles_v->givePermissionTo($permission);
                }
            }
        }

    }
}
