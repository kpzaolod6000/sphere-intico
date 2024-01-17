<?php

namespace Modules\Performance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\AIAssistant\Entities\AssistantTemplate;

class AIAssistantTemplateListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $defaultTemplate = [
            [
                'template_name'=>'subject',
                'template_module'=>'goal tracking',
                'prompt'=> "Generate a goal subject for an employee's goal related type to ##type##.",
                'field_json'=>'{"field":[{"label":"Goal Type","placeholder":"e.g.invoice, production,hiring","field_type":"text_box","field_name":"type"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'goal tracking',
                'prompt'=> "Generate a goal descriptions for an employee's goal title is ##title##.",
                'field_json'=>'{"field":[{"label":"Goal Title","placeholder":"e.g.Invoice Accuracy","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 0,
            ],

        ];
        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','Performance')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'Performance',
                        'prompt' => $temp['prompt'],
                        'field_json' => $temp['field_json'],
                        'is_tone' => $temp['is_tone'],
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
            }
        }
    }
}
