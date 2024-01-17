<?php

namespace Modules\Internalknowledge\Database\Seeders;

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
        $data['product_main_heading'] = 'Internal Knowledge (KB)';
        $data['product_main_description'] = '<p>An internal knowledge base is a company-made resource consisting of process documents and tools that members of the organization need to do their jobs properly. Its purpose is to mitigate organizational, technical, and learning difficulties often faced within companies that limit productivity.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = '<h2>Internal Knowledge Management</h2>';
        $data['dedicated_theme_description'] = '<p>The internal knowledge management system captures organizational knowledge that is known to long-term employees, making it accessible to the wider team and helping in maintaining organizational continuity.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"What are the benefits of Internal Knowledge?","dedicated_theme_section_description":"<p>Digital knowledge modules can reduce the costs associated with printing and distribution of physical books or articles.Overall, an internal knowledge module within a book or article enhances the readers experience by providing a well-organized, interactive, and easily accessible source of information and learning. It also benefits authors and publishers by offering new ways to engage readers and improve the quality and relevance of their content.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Mindmap","dedicated_theme_section_description":"<p>A mind map involves writing down a central theme and thinking of new and related ideas which radiate out from the center. By focusing on key ideas written down in your own words and looking for connections between them, you can map knowledge in a way that will help you to better understand and retain information.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Internalknowledge"},{"screenshots":"","screenshots_heading":"Internalknowledge"},{"screenshots":"","screenshots_heading":"Internalknowledge"},{"screenshots":"","screenshots_heading":"Internalknowledge"},{"screenshots":"","screenshots_heading":"Internalknowledge"}]';
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

        foreach ($data as $key => $value) {
            if (!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Internalknowledge')->exists()) {
                MarketplacePageSetting::updateOrCreate(
                    [
                        'name' => $key,
                        'module' => 'Internalknowledge'
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
        }
    }
}
