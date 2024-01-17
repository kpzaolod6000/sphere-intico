<?php

namespace Modules\Workflow\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Workflow\Entities\Workflow;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;
use Modules\Workflow\Entities\WorkflowUtility;

class CreateProjectLis
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        {   
            if(module_is_active('Workflow'))
            {
                $project = $event->project; 
                $request = $event->request;
                $workflow_module = WorkflowModule::where('submodule','New Project')->first(); 
                if($workflow_module)
                {
                    $workflows = Workflow::where('event',$workflow_module->id)->where('workspace',$project->workspace)->where('created_by',$project->created_by)->get();
                    $condition_symbol = Workflow::$condition_symbol; 
    
                    $symbolToOperator = [
                        '>' => function ($a, $b) { return $a > $b; },
                        '<' => function ($a, $b) { return $a < $b; },                    
                        '=' => function ($a, $b) { return $a == $b; },
                        '!=' => function ($a, $b) { return $a != $b; },
                    ];
                    foreach ($workflows as $key => $workflow)
                    { 
                        $conditions = !empty($workflow->json_data) ? json_decode($workflow->json_data) : [];
                        $status = false;
                        
                        foreach ($conditions as $key => $condition) 
                        {
                            if($condition->value)
                            {
                                $workflow_module_field = WorkflowModuleField::find($condition->preview_type);
                                if(!empty($workflow_module_field))
                                {
                                    $symbol = array_key_exists($condition->condition,$condition_symbol) ? $condition_symbol[$condition->condition] : '=';
                                    // for price
                                    
                                    if(($workflow_module_field->field_name == 'user') && isset($symbolToOperator[$symbol]))
                                    {
                                        $status = $symbolToOperator[$symbol]($project->user_id,$condition->value);
                                    }                                
                                    else if($workflow_module_field->field_name == 'name')
                                    {   
                                        $status = $symbolToOperator[$symbol]($request->name,$condition->value);   
                                    }
                                    else{
                                        break;
                                    }
                                }
                            }
    
                            if($status == false)
                            {
                                break;
                            }
                        }
                        
                        if($status == true)
                        {      
    
                            WorkflowUtility::call_do_this($workflow->id,$project);
                            
                        }
                    }
                }
                
            }
        }
    }
}
