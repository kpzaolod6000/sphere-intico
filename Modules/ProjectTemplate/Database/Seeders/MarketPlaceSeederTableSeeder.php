<?php

namespace Modules\ProjectTemplate\Database\Seeders;

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

        // $this->call("OthersTableSeeder");

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'ProjectTemplate';
        $data['product_main_description'] = '<p>This powerful feature is designed to streamline your project management process by allowing you to create, save, and effortlessly convert project templates into active projects.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Elevate Your Project Management Experience with Our Project Template Module';
        $data['dedicated_theme_description'] = '<p>Our Project Template Module empowers you to create and convert project templates seamlessly, ensuring swift project setup and standardized workflows. Take your project management to the next level.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Easily Creating Templates","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Creating Templates","description":"With this module, you can easily create project templates tailored to your specific needs.Also define project structures, tasks, milestones, and key parameters with this module."},"2":{"title":"Customization","description":"Take full advantage of customization options to make templates perfectly suited to your project requirements. Add, modify, or remove elements to craft the ideal template."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Converting Templates into Projects","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Saving Templates","description":"Once you`ve created your templates, you can save them for future use. Give them meaningful names and organize them for quick access."},"2":{"title":"Organizing Templates ","description":"Keep your templates neatly organized with category and tagging options. This ensures you can quickly locate the right template when needed."},"3":{"title":"Using Templates & Modifications","description":"When it`s time to start a new project, simply select a saved template. Your project will be initiated based on the template`s structure and parameters.Need to make changes on the fly? No problem! You can easily tweak your project during creation, adding or removing elements as necessary."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Project Template Benefits","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Efficiency & Consistency","description":"Save valuable time and reduce manual data entry. Use templates to kickstart your projects instantly.Templates ensure that every project follows a consistent structure and maintains standardized information, reducing errors."},"2":{"title":"Optimizing Templates ","description":"Discover best practices for creating efficient and effective templates, ensuring your templates work for you."}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"ProjectTemplate"},{"screenshots":"","screenshots_heading":"ProjectTemplate"},{"screenshots":"","screenshots_heading":"ProjectTemplate"},{"screenshots":"","screenshots_heading":"ProjectTemplate"},{"screenshots":"","screenshots_heading":"ProjectTemplate"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Account, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'ProjectTemplate')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'ProjectTemplate'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
