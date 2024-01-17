<?php

namespace Modules\Workflow\Database\Seeders;

use Nwidart\Modules\Facades\Module;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Workflow\Entities\WorkflowModule;
use Modules\Workflow\Entities\WorkflowModuleField;

class WorkflowDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            Model::unguard();

            $this->call(SidebarTableSeeder::class);
            $this->call(PermissionTableSeeder::class);
            $this->call(WorkflowdothisTableSeeder::class);
            if(module_is_active('LandingPage'))
            {
                $this->call(MarketPlaceSeederTableSeeder::class);
            }

            $this_module = Module::find('Workflow');
            $this_module->enable();
            
            $sub_module = [
                [
                    'module' => 'general',
                    'submodule' => 'New Users',
                    'fields' => [
                        ['name' => 'email', 'type' => 'text'],
                        ['name' => 'name', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'general',
                    'submodule' => 'New Invoice',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'Category'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'general',
                    'submodule' => 'New Proposal',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'Category'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Retainer',
                    'submodule' => 'New Retainer',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'Category'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Lead',
                    'submodule' => 'New Lead',
                    'fields' => [
                        ['name' => 'user', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'subject', 'type' => 'text'],
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'email', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Taskly',
                    'submodule' => 'New Project',
                    'fields' => [
                        ['name' => 'user', 'type' => 'select', 'model_name' => 'User'],
                        ['name' => 'name', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Account',
                    'submodule' => 'New Customer',
                    'fields' => [
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'email', 'type' => 'text'],
                        ['name' => 'Contact', 'type' => 'number'],
                        ['name' => 'Tax Number', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'Contract',
                    'submodule' => 'New Contract',
                    'fields' => [
                        ['name' => 'subject', 'type' => 'text'],
                        ['name' => 'user', 'type' => 'select','model_name' => 'User'],
                        ['name' => 'value', 'type' => 'number'],
                        ['name' => 'Type', 'type' => 'select','model_name'=>'ContractType'],
                    ],
                ],
                [
                    'module' => 'SupportTicket',
                    'submodule' => 'New Ticket',
                    'fields' => [
                        ['name' => 'Category', 'type' => 'select','model_name' => 'TicketCategory'],
                        ['name' => 'status', 'type' => 'select','model_name' => 'Ticket'],
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'email', 'type' => 'text'],
                        ['name' => 'subject', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Lead',
                    'submodule' => 'New Deal',
                    'fields' => [
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'price', 'type' => 'number'],
                        ['name' => 'client', 'type' => 'select','model_name' => 'DealUser'],
                    ],
                ],
                [
                    'module' => 'Appointment',
                    'submodule' => 'New Appointment',
                    'fields' => [
                        ['name' => 'name', 'type' => 'text'],
                        ['name' => 'Type', 'type' => 'select','model_name' => 'Appointment'], 
                    ],
                ],
                [
                    'module' => 'Pos',
                    'submodule' => 'New Purchase',
                    'fields' => [
                        ['name' => 'price', 'type' => 'text'],
                        ['name' => 'Category', 'type' => 'select', 'model_name' => 'pos_Category'],
                        ['name' => 'Warehouse', 'type' => 'select', 'model_name' => 'Warehouse'],
                        ['name' => 'Tax', 'type' => 'select', 'model_name' => 'Tax'],
                        ['name' => 'quantity', 'type' => 'text'],
                    ],
                ],
                [
                    'module' => 'Hrm',
                    'submodule' => 'New Award',
                    'fields' => [
                        ['name' => 'Employee', 'type' => 'select', 'model_name' => 'Award'],
                        ['name' => 'Award Type', 'type' => 'select', 'model_name' => 'AwardType'],
                    ],
                ],
                [
                    'module' => 'Training',
                    'submodule' => 'New Training',
                    'fields' => [
                        ['name' => 'Branch', 'type' => 'select', 'model_name' => 'Branch'],
                        ['name' => 'Trainer Option', 'type' => 'select', 'model_name' => 'Training'],
                        ['name' => 'Training Type', 'type' => 'select', 'model_name' => 'TrainingType'],
                        ['name' => 'Trainer', 'type' => 'select', 'model_name' => 'Trainer'],
                        ['name' => 'Employee', 'type' => 'select', 'model_name' => 'Employee'],
                        ['name' => 'Training Cost', 'type' => 'number'],
                    ],
                ],
                [
                    'module' => 'CMMS',
                    'submodule' => 'New Supplier',
                    'fields' => [
                        ['name' => 'Name', 'type' => 'text'],
                        ['name' => 'Location', 'type' => 'select', 'model_name' => 'Location'],
                        ['name' => 'Contact', 'type' => 'number'],
                        ['name' => 'Email', 'type' => 'text'],
                    ],
                ],
            ];
          
            foreach ($sub_module as $sm) { 
                $check = WorkflowModule::where('module', $sm['module'])->where('submodule', $sm['submodule'])->first();
                
                if (!$check) {
                    $sub_module_record = WorkflowModule::create([
                        'module' => $sm['module'],
                        'submodule' => $sm['submodule'],
                        'type' => 'company',
                    ]); 
                    foreach ($sm['fields'] as $field) {
                        WorkflowModuleField::create([
                            'workmodule_id' => $sub_module_record->id,
                            'field_name' => $field['name'],
                            'input_type' => $field['type'],
                            'model_name' => isset($field['model_name']) ? $field['model_name'] : null,
                        ]);
                    }
                }
            }
        }
    }
}
