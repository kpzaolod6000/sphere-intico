<?php

namespace Modules\Account\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\ChartOfAccountType;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */


    public function boot(){
        view()->composer(['settings*','productservice::create', 'productservice::edit'], function ($view) {
            if(\Auth::check() && \Auth::user()->type != 'super admin')
            {
                $active_module =  ActivatedModule();
                $dependency = explode(',','Account');

                $income_type = ChartOfAccountType::where('created_by', '=',creatorId())
                    ->where('workspace',getActiveWorkSpace())->where('name' ,'Income')->first();

                $incomeChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->where('type', $income_type->id)
                    ->where('created_by', creatorId())
                    ->get()
                    ->pluck('code_name', 'id');

                $incomeChartAccounts->prepend('Select Account', '');

                $expense_type = ChartOfAccountType::where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->whereIn('name', ['Expenses', 'Costs of Goods Sold'])
                    ->pluck('id')->toArray();
                $expenseChartAccounts = ChartOfAccount::select(\DB::raw('CONCAT(code, " - ", name) AS code_name, id'))
                    ->whereIn('type', $expense_type)
                    ->where('created_by', creatorId())
                    ->where('workspace', getActiveWorkSpace())
                    ->get()
                    ->pluck('code_name', 'id');
                $expenseChartAccounts->prepend('Select Account', '');

                if(!empty(array_intersect($dependency,$active_module)))
                {
                    $view->getFactory()->startPush('account_setting_sidebar', view('account::setting.sidebar'));
                    $view->getFactory()->startPush('account_setting_sidebar_div', view('account::setting.nav_containt_div'));
                    $view->getFactory()->startPush('add_column_in_productservice', view('account::setting.add_column_table',compact('incomeChartAccounts','expenseChartAccounts')));

                }


            }
        });
    }
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}
