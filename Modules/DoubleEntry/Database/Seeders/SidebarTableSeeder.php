<?php

namespace Modules\DoubleEntry\Database\Seeders;


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

        $check = Sidebar::where('title', __('Double Entry'))->where('parent_id', 0)->exists();

        if (!$check)
        {
            $doubleEntry = Sidebar::where('title', __('Double Entry'))->where('parent_id', 0)->where('type', 'company')->first();
            if ($doubleEntry == null) {
                $doubleEntry = Sidebar::create([
                    'title' => 'Double Entry',
                    'icon' => 'ti ti-scale',
                    'parent_id' => 0,
                    'sort_order' => 388,
                    'route' => null,
                    'permissions' => 'double entry manage',
                    'type' => 'company',
                    'module' => 'DoubleEntry',
                ]);
            }


            $journal_accounts = Sidebar::where('title', __('Journal Account'))->where('parent_id', $doubleEntry->id)->where('type', 'company')->first();
            if ($journal_accounts == null) {
                Sidebar::create([
                    'title' => 'Journal Account',
                    'icon' => '',
                    'parent_id' => $doubleEntry->id,
                    'sort_order' => 2,
                    'route' => 'journal-entry.index',
                    'permissions' => 'journal entry manage',
                    'module' => 'DoubleEntry',
                    'type' => 'company',
                ]);
            }
            $ledger_report = Sidebar::where('title', __('Ledger Summary'))->where('parent_id', $doubleEntry->id)->where('type', 'company')->first();
            if ($ledger_report == null) {
                Sidebar::create([
                    'title' => 'Ledger Summary',
                    'icon' => '',
                    'parent_id' => $doubleEntry->id,
                    'sort_order' => 3,
                    'route' => 'report.ledger',
                    'permissions' => 'report ledger',
                    'module' => 'DoubleEntry',
                    'type' => 'company',
                ]);
            }
            $balance_sheet_report = Sidebar::where('title', __('Balance Sheet'))->where('parent_id', $doubleEntry->id)->where('type', 'company')->first();
            if ($balance_sheet_report == null) {
                Sidebar::create([
                    'title' => 'Balance Sheet',
                    'icon' => '',
                    'parent_id' => $doubleEntry->id,
                    'sort_order' => 4,
                    'route' => 'report.balance.sheet',
                    'permissions' => 'report balance sheet',
                    'module' => 'DoubleEntry',
                    'type' => 'company',
                ]);
            }
            $profit_loss_report = Sidebar::where('title', __('Profit & Loss'))->where('parent_id', $doubleEntry->id)->where('type', 'company')->first();
            if ($profit_loss_report == null) {
                Sidebar::create([
                    'title' => 'Profit & Loss',
                    'icon' => '',
                    'parent_id' => $doubleEntry->id,
                    'sort_order' => 5,
                    'route' => 'report.profit.loss',
                    'permissions' => 'report profit loss',
                    'module' => 'DoubleEntry',
                    'type' => 'company',
                ]);
            }
            $trial_balance_report = Sidebar::where('title', __('Trial Balance'))->where('parent_id', $doubleEntry->id)->where('type', 'company')->first();
            if ($trial_balance_report == null) {
                Sidebar::create([
                    'title' => 'Trial Balance',
                    'icon' => '',
                    'parent_id' => $doubleEntry->id,
                    'sort_order' => 6,
                    'route' => 'report.trial.balance',
                    'permissions' => 'report trial balance',
                    'module' => 'DoubleEntry',
                    'type' => 'company',
                ]);
            }
            $report = Sidebar::where('title',__('Report'))->where('parent_id',$doubleEntry->id)->where('type','company')->first();
            if($report == null)
            {
                $report =  Sidebar::create( [
                    'title' => 'Report',
                    'icon' => '',
                    'parent_id' => $doubleEntry->id,
                    'sort_order' => 7,
                    'route' => null,
                    'permissions' => 'report manage',
                    'module' => 'DoubleEntry',
                    'type'=>'company',
                ]);
            }
            $sales_report = Sidebar::where('title',__('Sales'))->where('parent_id',$report->id)->where('type','company')->first();
            if($sales_report == null)
            {
                Sidebar::create( [
                    'title' => 'Sales',
                    'icon' => '',
                    'parent_id' => $report->id,
                    'sort_order' => 8,
                    'route' => 'report.sales',
                    'permissions' => 'report sales',
                    'module' => 'DoubleEntry',
                    'type'=>'company',
                ]);
            }
//            $receivables_report = Sidebar::where('title',__('Receivables'))->where('parent_id',$report->id)->where('type','company')->first();
//            if($receivables_report == null)
//            {
//                Sidebar::create( [
//                    'title' => 'Receivables',
//                    'icon' => '',
//                    'parent_id' => $report->id,
//                    'sort_order' => 9,
//                    'route' => 'report.receivables',
//                    'permissions' => 'report receivables',
//                    'module' => 'DoubleEntry',
//                    'type'=>'company',
//                ]);
//            }

        }



    }
}
