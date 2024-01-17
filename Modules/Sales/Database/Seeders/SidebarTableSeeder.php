<?php

namespace Modules\Sales\Database\Seeders;

use App\Models\Sidebar;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SidebarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $dashboard = Sidebar::where('title',__('Dashboard'))->where('parent_id',0)->where('type','company')->first();
        $sales_dash = Sidebar::where('title',__('Sales Dashboard'))->where('parent_id',$dashboard->id)->where('type','company')->first();
        if($sales_dash == null)
        {
            Sidebar::create( [
                'title' => 'Sales Dashboard',
                'icon' => '',
                'parent_id' => $dashboard->id,
                'sort_order' => 60,
                'route' => 'sales.dashboard',
                'permissions' => 'sales dashboard manage',
                'type' => 'company',
                'module' => 'Sales',
            ]);
        }

        $check = Sidebar::where('title',__('Sales'))->where('parent_id',0)->where('type','company')->exists();
        if(!($check))
        {
            $sales = Sidebar::where('title',__('Sales'))->where('parent_id',0)->where('type','company')->first();
            if($sales == null)
            {
                $sales = Sidebar::create( [
                    'title' => 'Sales',
                    'icon' => 'ti ti-file-invoice',
                    'parent_id' => 0,
                    'sort_order' => 360,
                    'route' => '',
                    'permissions' => 'sales manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $salesaccount = Sidebar::where('title',__('Account'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($salesaccount == null)
            {
            Sidebar::create([
                    'title' => 'Account',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 10,
                    'route' => 'salesaccount.index',
                    'permissions' => 'salesaccount manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $contact = Sidebar::where('title',__('Contact'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($contact == null)
            {
                Sidebar::create([
                    'title' => 'Contact',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 15,
                    'route' => 'contact.index',
                    'permissions' => 'contact manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $opportunities = Sidebar::where('title',__('Opportunities'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($opportunities == null)
            {
                Sidebar::create([
                    'title' => 'Opportunities',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 20,
                    'route' => 'opportunities.index',
                    'permissions' => 'opportunities manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $quote = Sidebar::where('title',__('Quote'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($quote == null)
            {
                Sidebar::create([
                    'title' => 'Quote',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 25,
                    'route' => 'quote.index',
                    'permissions' => 'quote manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $salesinvoice = Sidebar::where('title',__('Sales Invoice'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($salesinvoice == null)
            {
                Sidebar::create([
                    'title' => 'Sales Invoice',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 30,
                    'route' => 'salesinvoice.index',
                    'permissions' => 'salesinvoice manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $salesorder = Sidebar::where('title',__('Sales Order'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($salesorder == null)
            {
                Sidebar::create([
                    'title' => 'Sales Order',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 35,
                    'route' => 'salesorder.index',
                    'permissions' => 'salesorder manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $cases = Sidebar::where('title',__('Cases'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($cases == null)
            {
                Sidebar::create([
                    'title' => 'Cases',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 40,
                    'route' => 'commoncases.index',
                    'permissions' => 'case manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $stream = Sidebar::where('title',__('Stream'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($stream == null)
            {
                Sidebar::create([
                    'title' => 'Stream',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 45,
                    'route' => 'stream.index',
                    'permissions' => 'stream manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $document = Sidebar::where('title',__('Sales Document'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($document == null)
            {
                Sidebar::create([
                    'title' => 'Sales Document',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 50,
                    'route' => 'salesdocument.index',
                    'permissions' => 'salesdocument manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $call = Sidebar::where('title',__('Calls'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($call == null)
            {
                Sidebar::create([
                    'title' => 'Calls',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 55,
                    'route' => 'call.index',
                    'permissions' => 'call manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $meeting = Sidebar::where('title',__('Meeting'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($meeting == null)
            {
                Sidebar::create([
                    'title' => 'Meeting',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 60,
                    'route' => 'meeting.index',
                    'permissions' => 'meeting manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $report = Sidebar::where('title',__('Report'))->where('parent_id',$sales->id)->where('type','company')->first();
            if(!$report)
            {
                $report =  Sidebar::create( [
                    'title' => 'Report',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 65,
                    'route' => '',
                    'permissions' =>'sales report manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $quoteanl = Sidebar::where('title',__('Quote Analytics'))->where('parent_id',$report->id)->where('type','company')->first();
            if($quoteanl == null)
            {
                Sidebar::create( [
                    'title' => 'Quote Analytics',
                    'icon' => 'ti ti-file-invoice',
                    'parent_id' => $report->id,
                    'sort_order' => 10,
                    'route' => 'report.quoteanalytic',
                    'permissions' => 'quote report',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $inoanl = Sidebar::where('title',__('Sales Invoice Analytics'))->where('parent_id',$report->id)->where('type','company')->first();
            if($inoanl == null)
            {
                Sidebar::create( [
                    'title' => 'Sales Invoice Analytics',
                    'icon' => 'ti ti-file-invoice',
                    'parent_id' => $report->id,
                    'sort_order' => 15,
                    'route' => 'report.invoiceanalytic',
                    'permissions' => 'salesinvoice report',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $orderanl = Sidebar::where('title',__('Sales Order Analytics'))->where('parent_id',$report->id)->where('type','company')->first();
            if($orderanl == null)
            {
                Sidebar::create( [
                    'title' => 'Sales Order Analytics',
                    'icon' => 'ti ti-file-invoice',
                    'parent_id' => $report->id,
                    'sort_order' => 20,
                    'route' => 'report.salesorderanalytic',
                    'permissions' => 'salesorder report',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
            $setup = Sidebar::where('title',__('System Setup'))->where('parent_id',$sales->id)->where('type','company')->first();
            if($setup == null)
            {
                $setup =  Sidebar::create([
                    'title' => 'System Setup',
                    'icon' => '',
                    'parent_id' => $sales->id,
                    'sort_order' => 70,
                    'route' => 'account_type.index',
                    'permissions' => 'sales setup manage',
                    'type' => 'company',
                    'module' => 'Sales',
                ]);
            }
        }
    }
}
