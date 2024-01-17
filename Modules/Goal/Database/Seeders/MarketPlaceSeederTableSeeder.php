<?php

namespace Modules\Goal\Database\Seeders;

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
        $data['product_main_heading'] = 'Finacial Goal';
        $data['product_main_description'] = '<p>Set and track financial and business goals. Watch your progress, adjust your strategy, and grow your business. Depending on Account Module.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Financial Goals';
        $data['dedicated_theme_description'] = '<p>A financial goal is a target to aim for when managing your money. It can involve saving, spending, earning, or even investing. Creating a list of financial goals is vital to creating a budget</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"What are financial goals?","dedicated_theme_section_description":"","dedicated_theme_section_cards":{"1":{"title":"Financial goals","description":"Financial goals are the personal, big-picture objectives you set for how you’ll save and spend money. They can be things you hope to achieve in the short term or further down the road. Either way, it’s often easier to reach your goals if you identify them in advance."},"2":{"title":"Examples of financial goals","description":"Think about what’s important to you as you begin to set goals. It’s completely normal to have several goals, and for them to change over time."},"3":{"title":"Why financial goals matter","description":"Having financial goals can help shape your future by influencing the actions you take today. For example, say your goal is to pay off a colossal credit card bill. You might cut back on takeout dinners and use the money you save to make extra payments instead. Without establishing that goal, you’re more likely to continue spending as usual while your debt piles up."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Types of Financial Goals","dedicated_theme_section_description":" <p>Although you can have a wide variety of goals, you can broadly classify each of these goals within a specific time frame so that your priorities become clear. Categorizing as per time frame helps you visualize the goals and pace yourself accordingly. To ensure your life is planned and on track, you must focus on putting clear timelines when setting goals. This will make you more productive and effective. Here are 3 types of goals:1) Short-Term Goals 2) Medium-Term Goals 3) Long-Term Goals<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null},"2":{"title":null,"description":null},"3":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"},{"screenshots":"","screenshots_heading":"Project"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'goal')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'goal'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
