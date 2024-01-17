<?php

namespace Modules\Workflow\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LandingPage\Entities\MarketplacePageSetting;

class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'Workflow';
        $data['product_main_description'] = '<p>Effortlessly streamline tasks using Custom Workflow automation, minimizing manual work and stress. Customize actions based on events, like auto-emails for bill settlement and seamless integration for efficient operations.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>More <b>Effective </b>With Workflow</h2>';
        $data['dedicated_theme_description'] = '<p>A workflow is a series of interconnected activities that are executed in a specific order to achieve a desired result.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Set up your workflow","dedicated_theme_section_description":"<p>Workflow automation triggers notifications (email, Slack, Telegram, Twitter) for new invoices, user leads, projects. It monitors status and sends personalized alerts. Streamlines communication and updates for efficient processes.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"What Is Use Of Workflow??","dedicated_theme_section_description":"<p>Workflow involves organizing tasks into a logical sequence to enhance productivity. Define tasks, establish their order, set milestones, allocate time, and monitor progress. Efficient workflow boosts efficiency and task management.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Workflow"},{"screenshots":"","screenshots_heading":"Workflow"},{"screenshots":"","screenshots_heading":"Workflow"}]';
        $data['addon_heading'] = '<h2>Why choose dedicated modules<b> for Your Business?</b></h2>';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Workflow')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Workflow'
                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
