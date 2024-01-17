<?php

namespace Modules\Performance\Database\Seeders;

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
        $data['product_main_heading'] = 'Performance';
        $data['product_main_description'] = '<p>Boost employee productivity with custom KPIs. Track employee performance, share feedback, and help them reach company targets. Depending on Hrm Module.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Help Your Employees Become More Productive';
        $data['dedicated_theme_description'] = '<p>Set benchmarks and grade your employee performance. Find top-performers and reward them for their hard work and improvement.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"",
            "dedicated_theme_section_heading":"Rating System",
            "dedicated_theme_section_description":"<p>Empower employee growth. Schedule skills training, track expenses, and watch your employees become better at their work. Boost employee productivity with custom KPIs. Track employee performance, share feedback, and help them reach company targets.Give the stars your employee on their performance.Pick key areas and skills and create a rating system to help your employees understand where they need to improve.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},

            {"dedicated_theme_section_image":"",
                "dedicated_theme_section_heading":"Indicator Appraisal",
                "dedicated_theme_section_description":"<p>Get a clear overview of the performance of your departments. Identify areas of improvement in real-time, and streamline processes for better performance.<\/p>",
                "dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},

                {"dedicated_theme_section_image":"",
                    "dedicated_theme_section_heading":"Goal Tracking System",
                    "dedicated_theme_section_description":"<p>Experience the transformative impact of transparent goal setting and tracking with Gole System`s intuitive interface. Drive your organization`s success by aligning your team`s efforts and achieving excellence together.<\/p>",
                    "dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Performance"},{"screenshots":"","screenshots_heading":"Performance"},{"screenshots":"","screenshots_heading":"Performance"},{"screenshots":"","screenshots_heading":"Performance"},{"screenshots":"","screenshots_heading":"Performance"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Performance')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Performance'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
