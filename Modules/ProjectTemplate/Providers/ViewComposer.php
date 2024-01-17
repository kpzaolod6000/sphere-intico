<?php

namespace Modules\ProjectTemplate\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Taskly\Entities\Project;

class ViewComposer extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */


    public function boot(){
        view()->composer(['taskly::projects.index','taskly::projects.show'], function ($view)
        {
            if(\Auth::check())
            {
                $id = \Request::segment(2);
                $project = Project::find($id);
                $active_module =  ActivatedModule();
                $dependency = explode(',','ProjectTemplate');
                if(!empty(array_intersect($dependency,$active_module)))
                {
                    $view->getFactory()->startPush('project_template_button', view('projecttemplate::setting.template-button'));
                    if($project)
                    {
                        $view->getFactory()->startPush('addButtonHook', view('projecttemplate::setting.template-convert',compact('project')));
                    }
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
