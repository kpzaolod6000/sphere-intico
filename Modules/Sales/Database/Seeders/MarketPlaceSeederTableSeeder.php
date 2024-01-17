<?php

namespace Modules\Sales\Database\Seeders;

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
        $data['product_main_heading'] = 'Sales';
        $data['product_main_description'] = '<p>Never lose a client to poor sales management ever again! Easily manage your sales, Calls, Documents, quotes, meeting schedule, Opportunities, and orders from one intuitive dashboard.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Salesy Makes It Super Easy For You To Manage Your Sales';
        $data['dedicated_theme_description'] = '<p>You can also find the individual overview of each of these elements broken down in percentages with their respective status.  Additionally, know the top due tasks, meeting schedule, and monthly calls on this dashboard.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"The Smartest Way To Manage Your Sales","dedicated_theme_section_description":"<p>Salesy monitors your sales performance like a coach and lets you know whether you are on track. Get real time reports about every sales activity you make, make smarter decisions, and manage your business’s general sales more efficiently.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Manage All Your Sales From One Place","description":"Manage every aspect of your business sales from one place. Get sales updates on a daily, weekly or monthly basis. Monitor your sales, leads and accounts from a single comprehensive dashboard."},"2":{"title":"Access Your Clients’ Information","description":"Easily view and control the details of all your clients. Create user accounts and edit existing user information, manage your contacts, and do a lot more from anywhere - with a single tool."},"3":{"title":"Manage All Your Sales Management Tasks Quickly And Easily From One Place","description":"Control and keep track of your orders, invoices, and performance with ease. Easily track current trends, leads, and more. Schedule meetings, manage tasks, and carry out complex tasks in a few simple clicks."}}}, {"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Manage Your Sales From One Place","dedicated_theme_section_description":"<p>Access a wide range of easily customizable features in one place.Get an overview of your invoices, quotes and sales order- all under one roof.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Convert Quotes to Sales Order","description":"Initially generated sales quotes can be altered, and updated as per the modification of the sales order on the particular sales quote."},"2":{"title":"Manage Your Payments Easily","description":"Get paid for work done, fast. Integrate several payment options for diverse clients and make the payment process stress-free. Easily safeguard your clients’ payment by using Stripe, PayPal, Flutterwave, and more."},"3":{"title":"Get Instant Notifications","description":"Integrate the Twilio app and never miss an important notification again. Get notified when tasks or orders are completed and get notifications about meetings and new orders sent to your mobile phone via text."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Modify Vital Sales Info With Ease","dedicated_theme_section_description":"<p>Modify and update your generated sales quotes with ease. Convert your quotes to invoices, get accurate information, and add new orders and products without stress.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Essential Information At Your Fingertips","dedicated_theme_section_description":"<p>Access a wide database of sales information under one tab. Easily filter through and sort details by different parameters. View invoices and orders, and manage information on a daily, weekly or monthly basis.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Sales"},{"screenshots":"","screenshots_heading":"Sales"},{"screenshots":"","screenshots_heading":"Sales"},{"screenshots":"","screenshots_heading":"Sales"},{"screenshots":"","screenshots_heading":"Sales"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'Sales')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'Sales'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
