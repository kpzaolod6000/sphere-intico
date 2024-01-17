<?php

namespace Modules\SupportTicket\Database\Seeders;

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
                'template_module'=>'ticket',
                'prompt'=> "generate example of  subject for bug in ecommerce base website support ticket",
                'field_json'=>'{"field":[{"label":"Ticket Description of Bug","placeholder":"e.g.Bug Summary","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'ticket',
                'prompt'=> "generate support ticket description of  subject for ##subject## ",
                'field_json'=>'{"field":[{"label":"Ticket Subject","placeholder":"e.g.Error Message Displayed","field_type":"textarea","field_name":"subject"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'reply_description',
                'template_module'=>'reply',
                'prompt'=> "generate a short  replay note for support ticket that topic is '##title##'. user must be note that '##description##'.",
                'field_json'=>'{"field":[{"label":"Ticket Title","placeholder":"Getting some issues while installation products.","field_type":"text_box","field_name":"title"},{"label":"Description","placeholder":"isuue is in his console account not in our product please follow google console api key creation step","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'note',
                'template_module'=>'reply',
                'prompt'=> "generate a  note for support ticket that topic is '##title##'. in that note include this points '##description##'.",
                'field_json'=>'{"field":[{"label":"Ticket Title","placeholder":"Getting some issues while installation products.","field_type":"text_box","field_name":"title"},{"label":"Description","placeholder":"isuue is in his console account not in our product please follow google console api key creation step","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'title',
                'template_module'=>'knowledge',
                'prompt'=> "list out  title of Knowledge base in support ticket system for customer the  title relate to the category of '##categoty##'",
                'field_json'=>'{"field":[{"label":"Knowledge Category Title","placeholder":"Installation","field_type":"text_box","field_name":"categoty"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'knowledge',
                'prompt'=> "generate catchy detail user friendly description for this knowledge base  title : '##title##' please note that description should be usable for support ticket system",
                'field_json'=>'{"field":[{"label":"Title","placeholder":" How to Install Our Software","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'title',
                'template_module'=>'knowledge_category',
                'prompt'=> "list out category title of Knowledge in support ticket system for customer  the category title relate to the topic of '##title##'",
                'field_json'=>'{"field":[{"label":"Topic","placeholder":"Product Information","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'title',
                'template_module'=>'faq',
                'prompt'=> "IT company's web service support ticket system,please  suggested  some  number  of only topic name of Question  that asked by users repeatedlly in web service support relate to ##relate##.",
                'field_json'=>'{"field":[{"label":"FAQ Description","placeholder":"Installation","field_type":"text_box","field_name":"relate"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'faq',
                'prompt'=> "generate catchy detail user friendly description for this question topic : '##title##' please note that description should be usable for support ticket system",
                'field_json'=>'{"field":[{"label":"FAQ Title","placeholder":"Product Information","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 0,
            ],
        ];

        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','SupportTicket')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'SupportTicket',
                        'prompt' => $temp['prompt'],
                        'field_json' => $temp['field_json'],
                        'is_tone' => $temp['is_tone'],
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
            }
        }

        // $this->call("OthersTableSeeder");
    }
}
