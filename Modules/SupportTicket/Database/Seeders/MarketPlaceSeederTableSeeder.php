<?php

namespace Modules\SupportTicket\Database\Seeders;

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
        $data['product_main_heading'] = 'SupportTicket';
        $data['product_main_description'] = '<p>Your clients are happier and more likely to trust you if you answer their queries fast. Support Ticket makes it super easy for your clients to create tickets, and allows you to resolve them quickly and easily.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'The smartest way to manage your customer support tickets';
        $data['dedicated_theme_description'] = '<p>Support Ticket is the ONLY ticket management tool that makes it easy for you to manage support tickets smoothly. You and your team can access your clients’ tickets, respond to them, and resolve them - in a few clicks.</p>';
       $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Improve your customer service experience","dedicated_theme_section_description":"<p>Keep track of all your tickets and agents with ease. support ticket centralized dashboard puts all clients tickets, as well as all your agents in one place. This way, it’ll be easy to ensure that queries are being addressed at the right time.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Create a fast and simple ticket system for your clients","description":"Make it super easy for your clients to create tickets. With TicketGo SaaS, your clients can open tickets in seconds. They also receive instant responses from your agents - so they can rest, assured that help is on the way."},"2":{"title":"Manage all customer support tickets from a central dashboard.","description":"Attend to your customers’ queries in record time, and gain their trust. With TicketGo SaaS, multiple admins from your team can access and manage tickets."},"3":{"title":"Create a quick and easy ticket generation process","description":"Support Ticket makes creating tickets fun and simple for your clients. Your clients can select their query from a list of FAQs, fill the simplified form, attach files, submit, and receive a unique ticket ID - In a matter of seconds!"}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Organize & Manage Your Support Ticket System","dedicated_theme_section_description":"<p>Get easy access to open and closed ticket statistics. A visual presentation of categories and a monthly generated ticket chart are presented.<\/p>","dedicated_theme_section_cards":{"1":{"title":"Organize, manage, and control all support tickets","description":"Manage and control your entire support ticket system without lifting a finger. Create ticket categories, organize tickets, respond to them, and edit their status. Add multiple admins and agents, assign customer requests to them - and do so much more!"},"2":{"title":"Keep track of everything in one place","description":"View all your tickets and agents at a glance. Access the statistics of all your tickets and agents. View a monthly support ticket chart, get a graphical presentation of all ticket categories, and more! - in just one dashboard."},"3":{"title":"Knowledge Base Module","description":"The Frequently Asked Questions are always convenient no matter what. What about the feature which adds more benefit to that? Here, this Knowledge Base Module is helping you with the same. You can update the “Knowledgebase Category” according to your requirements and familiarity, and you can give a thorough description under the particular title."}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Communicate with your customers seamlessly","dedicated_theme_section_description":"<p>The custom ticket form has made the process of questioning easy and reliable. Once you submit the ticket, a unique link has been generated for you for further communication which makes the whole process smooth.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_heading":"Make it easy for clients to follow up on their tickets","dedicated_theme_section_description":"<p>Add new tickets as and when required. Get a detailed list of all the generated tickets with their respective ticket IDs, categories, subject, status, and essential information. Edit them by adding a new response or changing the status with a simple click.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'SupportTicket')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'SupportTicket'

                ],
                [
                    'value' => $value
                ]);
            }
        }

    }
}
