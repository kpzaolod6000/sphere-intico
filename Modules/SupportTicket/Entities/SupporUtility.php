<?php

namespace Modules\SupportTicket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Permission;


class SupporUtility extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\SupportTicket\Database\factories\SupporUtilityFactory::new();
    }

    public static function GivePermissionToRoles($role_id = null, $rolename = null)
    {
        $client_permissions = [

            'ticket manage',
            'supportticket manage',
            'ticket create',
            'ticket edit',
            'ticket  delete',
            'ticket show',
            'ticket reply',
        ];

        $staff_permissions = [

            'ticket manage',
            'supportticket manage',
            'ticket create',
            'ticket edit',
            'ticket  delete',
            'ticket show',
            'ticket reply',
        ];

        if ($role_id == Null) {
            // client
            $roles_c = Role::where('name', 'client')->get();
            foreach ($roles_c as $role) {
                foreach ($client_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();
                    if (!$role->hasPermission($permission_c)) {
                        $role->givePermission($permission);
                    }
                }
            }


            // vender
            $roles_v = Role::where('name', 'staff')->get();

            foreach ($roles_v as $role) {
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if (!$role->hasPermission($permission_v)) {
                        $role->givePermission($permission);
                    }
                }
            }
        } else {
            if ($rolename == 'client') {
                $roles_c = Role::where('name', 'client')->where('id', $role_id)->first();
                foreach ($client_permissions as $permission_c) {
                    $permission = Permission::where('name', $permission_c)->first();
                    if ($permission) {
                        if (!$roles_c->hasPermission($permission_c)) {
                            $roles_c->givePermission($permission);
                        }
                    }
                }
            } elseif ($rolename == 'staff') {
                $roles_v = Role::where('name', 'staff')->where('id', $role_id)->first();
                foreach ($staff_permissions as $permission_v) {
                    $permission = Permission::where('name', $permission_v)->first();
                    if ($permission) {
                        if (!$roles_v->hasPermission($permission_v)) {
                            $roles_v->givePermission($permission);
                        }
                    }
                }
            }
        }
    }
}
