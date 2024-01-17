<?php

namespace Modules\Sales\Database\Seeders;

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
                'template_name'=>'description',
                'template_module'=>'salesaccount',
                'prompt'=> "Generate a descriptive response for a given ##title##. The response should be detailed, engaging, and informative, providing relevant information and capturing the reader's interest",
                'field_json'=>'{"field":[{"label":"Asset name","placeholder":"HR may provide some devices ","field_type":"text_box","field_name":"title"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'contact',
                'prompt'=>"generate a tiny and innovative paragraph note which i can attechted with my contant detail please includes seed word : ##keywords##",
                'field_json'=>'{"field":[{"label":"Seed words","placeholder":"e.g. any time my phone number can be change","field_type":"text_box","field_name":"keywords"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'opportunities',
                'prompt'=>"list out Opportunities name for Campaign : ##name## for product saling company",
                'field_json'=>'{"field":[{"label":"Task Instruction","placeholder":"e.g.Fashion Fusion","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'opportunities',
                'prompt'=>"give brief decription of Opportunity detail and cover all point with it's importance for this Opportunity '##name##'",
                'field_json'=>'{"field":[{"label":"Opportunity name","placeholder":"e.g.Fashion Fusion Frenzy","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'quote',
                'prompt'=>"generate a name of quotation related product saling  using seed  keywords are : ##keywords##",
                'field_json'=>'{"field":[{"label":"Seed words","placeholder":"e.g.ProQuoteSale","field_type":"text_box","field_name":"keywords"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'quote',
                'prompt'=>"generate a description of  quotation related product saling please note that description should be justify this title  : ##name##",
                'field_json'=>'{"field":[{"label":"Quote name","placeholder":"e.g.ProQuoteSale","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'quote item',
                'prompt'=>"generate a description of quote related this item description should be justify this itme name : ##name##",
                'field_json'=>'{"field":[{"label":"Quote Item name","placeholder":"e.g.name of Quote item","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'salesinvoice',
                'prompt'=>"generate innovative name of Invoice for this Sales Orders:##name##.",
                'field_json'=>'{"field":[{"label":"Salesorder name","placeholder":"e.g.SaleMaster","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'salesinvoice',
                'prompt'=>"generate a description of invoice related product saling please note that description should be justify this title : ##name##",
                'field_json'=>'{"field":[{"label":"Invoice name","placeholder":"e.g.Speedy Sales Impact Invoice","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'salesinvoice item',
                'prompt'=>"generate a description of invoice  related this item description should be justify this itme name : ##name##",
                'field_json'=>'{"field":[{"label":"Invoice Item name","placeholder":"e.g.name of Invoice item","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'salesorder',
                'prompt'=>"generate a name of Sales Order related saling that saleorder name shoul relate ##name##'",
                'field_json'=>'{"field":[{"label":"Quote name","placeholder":"e.g.ProQuoteSale","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'salesorder',
                'prompt'=>"generate a description of  Sales Order related product saling please note that description should be justify this name  : ##name##",
                'field_json'=>'{"field":[{"label":"Salesorder name","placeholder":"e.g.SaleMaster","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'salesorder item',
                'prompt'=>"generate a description of salesorder  related this item description should be justify this itme name : ##name##",
                'field_json'=>'{"field":[{"label":"Salesorder Item name","placeholder":"e.g.name of Salesorder item","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'cases',
                'prompt'=>"generate name of case for ##resoan##, case should be relate to product saling business",
                'field_json'=>'{"field":[{"label":"Decribe resoan of case" ,"placeholder":"e.g.name of Invoice item","field_type":"textarea","field_name":"resoan"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'cases',
                'prompt'=>"generate detail & brief description of this perticular case :##name##",
                'field_json'=>'{"field":[{"label":"Case name","placeholder":"e.g.HarmfulGoods Lawsuit","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'salesdocument',
                'prompt'=>"generate sutiable and valuable name of document please note name should justify the document description  '##description##' .",
                'field_json'=>'{"field":[{"label":"Document Name","placeholder":"e.g. verification file ","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'salesdocument',
                'prompt'=>"Generate a description based on a given document name:##name##. The document name: ##name## represents a specific file or document, and you need a descriptive summary or overview of its contents. Please provide a clear and concise description that captures the main points, purpose, or key information contained within the document. Aim to create a brief but informative description that gives the reader an understanding of what they can expect when accessing or reviewing the document.",
                'field_json'=>'{"field":[{"label":"Document name","placeholder":"","field_type":"text_box","field_name":"name"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'call',
                'prompt'=>"generate call for tile that justify this resoan ##description## for the call ",
                'field_json'=>'{"field":[{"label":"Call Resoan","placeholder":"e.g.Time Management Strategy Call","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'call',
                'prompt'=>"generate a description of call  related this call description should be justify this call resoan : ##description##",
                'field_json'=>'{"field":[{"label":"Call title","placeholder":"e.g.Time Management Strategy Call","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 1,
            ],
            [
                'template_name'=>'name',
                'template_module'=>'meeting',
                'prompt'=>"Generate a meeting title that is catchy and informative. The title should effectively convey the purpose and focus of the meeting, whether it's for ##purpose##. Please aim to create a title that grabs the attention of participants, reflects the importance of the meeting, and provides a clear understanding of what will be discussed or accomplished during the session.",
                'field_json'=>'{"field":[{"label":"Meeting purpose","placeholder":"e.g.conference, workshop","field_type":"textarea","field_name":"purpose"}]}',
                'is_tone'=> 0,
            ],
            [
                'template_name'=>'description',
                'template_module'=>'meeting',
                'prompt'=>"Write a short and innovative informable description for meeting which about : '##title##' with includes this special information ##description##",
                'field_json'=>'{"field":[{"label":"Meeting topic","placeholder":"discuss product sales","field_type":"text_box","field_name":"title"},{"label":"Instruction for meeting ","placeholder":"please come with your presentation","field_type":"textarea","field_name":"description"}]}',
                'is_tone'=> 0,
            ],

        ];
        foreach($defaultTemplate as $temp)
        {
            $check = AssistantTemplate::where('template_module',$temp['template_module'])->where('module','Sales')->where('template_name',$temp['template_name'])->exists();
            if(!$check)
            {
                AssistantTemplate::create(
                    [
                        'template_name' => $temp['template_name'],
                        'template_module' => $temp['template_module'],
                        'module' => 'Sales',
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
