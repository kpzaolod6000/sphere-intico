<?php

namespace Modules\Sales\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sales\Entities\UserDefualtView;
Use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\WorkSpace;
use Rawilk\Settings\Support\Context;

class SalesUtility extends Model
{
    public static function userDefualtView($request)
    {

        $userId      = \Auth::user()->id;
        $defaultView = UserDefualtView::where('module', $request->module)->where('user_id', $userId)->first();

        if(empty($defaultView))
        {
            $userView = new UserDefualtView();
        }
        else
        {
            $userView = $defaultView;
        }

        $userView->module  = $request->module;
        $userView->route   = $request->route;
        $userView->view    = $request->view;
        $userView->user_id = $userId;
        $userView->save();
    }

    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3)
        {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        }
        else
        {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for($i = 0; $i < count($C); ++$i)
        {
            if($C[$i] <= 0.03928)
            {
                $C[$i] = $C[$i] / 12.92;
            }
            else
            {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if($L > 0.179)
        {
            $color = 'black';
        }
        else
        {
            $color = 'white';
        }

        return $color;
    }

    public static function templateData()
    {
        $arr              = [];
        $arr['colors']    = [
            '003580',
            '666666',
            '6676ef',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];

        return $arr;
    }


    public static function totalTaxRate($taxes)
    {
        if(module_is_active('ProductService'))
        {
            $taxArr  = explode(',', $taxes);
            $taxRate = 0;

            foreach($taxArr as $tax)
            {

                $tax     = \Modules\ProductService\Entities\Tax::find($tax);
                $taxRate += !empty($tax->rate) ? $tax->rate : 0;
            }

            return $taxRate;
        }
        else{
            return 0;
        }
    }

    public static function tax($taxes)
    {
        if(module_is_active('ProductService'))
        {
            $taxArr = explode(',', $taxes);
            $taxes  = [];
            foreach($taxArr as $tax)
            {
                $taxes[] = \Modules\ProductService\Entities\Tax::find($tax);
            }
            return $taxes;
        }else{
            return [];
        }
    }

    public static function taxRate($taxRate, $price, $quantity,$discount = 0)
    {
        return ($taxRate / 100) * (($price * $quantity)-$discount);
    }

    public static function ownerIdforQuote($id){
        $user = User::where(['id' => $id])->first();
        if(!is_null($user)){
            return $user->id;
        }
        return 0;
    }

    public static function invoice_payment_settings($id)
    {
        $data = [];

        $user = User::where(['id' => $id])->first();

        if(!is_null($user)){
            $data = DB::table('admin_payment_settings');
            $data->where('created_by', '=', $user->id);
            $data = $data->get();
        }

        $res = [];

        foreach ($data as $key => $value) {
            $res[$value->name] = $value->value;
        }

        return $res;
    }

    public static function ownerIdforSalesorder($id){
        $user = User::where(['id' => $id])->first();
        if(!is_null($user)){
            return $user->id;
        }
        return 0;
    }

    public static function ownerIdforInvoice($id){
        $user = User::where(['id' => $id])->first();
        if(!is_null($user)){
            return $user->id;
        }
        return 0;
    }


    public static function GivePermissionToRoles($role_id = null,$rolename = null)
    {
        $client_permissions=[
            'sales manage',
            'contact manage',
            'contact show',
            'opportunities manage',
            'opportunities show',
            'salesaccount manage',
            'salesaccount show',
            'sales setup manage',
            'accountindustry manage',
            'salesdocument manage',
            'salesdocument show',
            'call manage',
            'call show',
            'meeting manage',
            'meeting show',
            'stream manage',
            'case manage',
            'case show',
            'quote manage',
            'quote show',
            'shippingprovider manage',
            'salesorder manage',
            'salesorder show',
            'salesinvoice manage',
            'salesinvoice show',
            'sales report manage',
            'quote report',
        ];


        $staff_permissions=[
            'sales manage',
            'contact manage',
            'contact show',
            'opportunities manage',
            'opportunities show',
            'salesaccount manage',
            'salesaccount show',
            'sales setup manage',
            'accountindustry manage',
            'salesdocument manage',
            'salesdocument show',
            'call manage',
            'call show',
            'meeting manage',
            'meeting show',
            'stream manage',
            'case manage',
            'case show',
            'quote manage',
            'quote show',
            'shippingprovider manage',
            'salesorder manage',
            'salesorder show',
            'salesinvoice manage',
            'salesinvoice show',
            'sales report manage',
            'quote report',
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
            "quote_prefix" => "#QUO",
            "salesorder_prefix" => "#SLO",
            "salesinvoice_prefix" => "#INO",
            "quote_template" => "template1",
            "salesorder_template" => "template1",
            "salesinvoice_template" => "template1",
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
