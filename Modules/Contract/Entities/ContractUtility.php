<?php

namespace Modules\Contract\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
Use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\WorkSpace;
use Rawilk\Settings\Support\Context;

class ContractUtility extends Model
{
    use HasFactory;

    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $client_permissions=[
            'contract manage',
            'comment create',
            'comment delete',
            'contract note create',
            'contract note delete',
        ];

        $staff_permissions=[
            'contract manage',
            'comment create',
            'comment delete',
            'contract note create',
            'contract note delete',
        ];

        if($role_id == Null)
        {
            // client
            $roles_c = Role::where('name','client')->get();
            foreach($roles_c as $role)
            {
                foreach($client_permissions as $permission_c){
                    $permission = Permission::where('name',$permission_c)->first();
                    $role->givePermissionTo($permission);
                }
            }

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
            if($rolename == 'client')
            {
                $roles_c = Role::where('name','client')->where('id',$role_id)->first();
                foreach($client_permissions as $permission_c){
                    $permission = Permission::where('name',$permission_c)->first();
                    $roles_c->givePermissionTo($permission);
                }
            }
            elseif($rolename == 'staff')
            {
                $roles_v = Role::where('name','staff')->where('id',$role_id)->first();
                foreach($staff_permissions as $permission_v){
                    $permission = Permission::where('name',$permission_v)->first();
                    $roles_v->givePermissionTo($permission);
                }
            }
        }

    }


    public static function defaultdata($company_id = null,$workspace_id = null)
    {
        $company_setting = [
            "contract_prefix" => "#CON",
        ];
        if($company_id == Null)
        {
            $companys = User::where('type','company')->get();
            foreach($companys as $company)
            {
                $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
                foreach($WorkSpaces as $WorkSpace)
                {

                    $userContext = new Context(['user_id' => $company->id,'workspace_id'=> !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
                    foreach($company_setting as $key =>  $p)
                    {
                            \Settings::context($userContext)->set($key, $p);
                    }
                }
            }
        }elseif($workspace_id == Null){
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpaces = WorkSpace::where('created_by',$company->id)->get();
            foreach($WorkSpaces as $WorkSpace)
            {
                $userContext = new Context(['user_id' => $company->id,'workspace_id'=> !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
                foreach($company_setting as $key =>  $p)
                {
                        \Settings::context($userContext)->set($key, $p);
                }
            }
        }else{
            $company = User::where('type','company')->where('id',$company_id)->first();
            $WorkSpace = WorkSpace::where('created_by',$company->id)->where('id',$workspace_id)->first();
            $userContext = new Context(['user_id' => $company->id,'workspace_id'=> !empty($WorkSpace->id) ? $WorkSpace->id : 0]);
            foreach($company_setting as $key =>  $p)
            {
                    \Settings::context($userContext)->set($key, $p);
            }
        }
    }

}
