<?php

namespace Modules\Sales\Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // slack notification
        $notifications = [
            'New Quote','New Sales Order','New Sales Invoice','New Sales Invoice Payment','Meeting Assigned'
        ];
        $types = [
            'slack','telegram','twilio','whatsapp'
        ];
        foreach($types as $t){
            foreach($notifications as $n){
                $ntfy = Notification::where('action',$n)->where('type',$t)->where('module','Sales')->count();
                if($ntfy == 0){
                    $new = new Notification();
                    $new->action = $n;
                    $new->status = 'on';
                    $new->module = 'Sales';
                    $new->type = $t;
                    $new->save();
                }
            }
        }

        // email notification
        $notifications = [
            'Meeting assigned','New Quotation','New Sales Order','New Sales Invoice','Sales Invoice Sent'
        ];
        $permissions = [
            'meeting manage',
            'quote manage',
            'salesorder manage',
            'salesinvoice manage',
            'salesinvoice manage'


        ];
        foreach($notifications as $key=>$n){
            $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','Sales')->count();
            if($ntfy == 0){
                $new = new Notification();
                $new->action = $n;
                $new->status = 'on';
                $new->permissions = $permissions[$key];
                $new->module = 'Sales';
                $new->type = 'mail';
                $new->save();
            }
        }
    }
}
