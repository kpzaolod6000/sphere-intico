<?php

namespace Modules\GoogleDrive\Database\Seeders;

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
        $data['product_main_heading'] = 'Google Drive';
        $data['product_main_description'] = '<p>Discover the freedom of accessing your Google Drive files directly from Workdo-dash. No more switching between applications or dealing with cumbersome downloads. With this integration, your Google Drive documents, files, and folders are at your fingertips, effortlessly accessible within the Workdo-dash interface. Edit and view a wide range of file types – from Word documents to Excel spreadsheets, text files, and images – using the Google File Viewer/Editor, all without the need to save files locally.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'Seamless Google Drive Integration';
        $data['dedicated_theme_description'] = '<p>Experience a new era of efficiency and collaboration with our cutting-edge Google Drive integration.</p>';
        $data['dedicated_theme_sections'] = '[{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Grid View to Visualize Your Files and Images with Clarity","dedicated_theme_section_description":"<p>Introducing the Google Drive Module Grid View &ndash; a dynamic visual hub where your files and images come to life. With seamless integration between Workdo-dash and Google Drive, this innovative feature showcases your documents in a stunning grid layout. Effortlessly browse through your Google Drive files and images right from within our platform, providing an immersive and intuitive experience. Say goodbye to clutter and confusion &ndash; our grid view offers clarity at a glance, allowing you to easily locate and access the files that matter most.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Google Drive List View","dedicated_theme_section_description":"<p>This feature presents your Google Drive files and images in a sleek and organized list format. Navigating through your documents has never been smoother &ndash; effortlessly scroll, search, and locate the files you need, all within the familiar confines of our platform. Simplify your workflow and enhance your productivity as you access, edit, and collaborate on documents, right from the convenience of the List View.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Assign Google Drive Folders to Submodules","dedicated_theme_section_description":"<p>Unleash the full potential of collaboration and organization with our groundbreaking feature &ndash; the ability to seamlessly link Google Drive folders with submodules. With this innovative enhancement, Workdo-dash empowers you to synchronize your Google Drive documents with specific submodules, ensuring that your files are precisely where you need them. Whether you&#39;re managing projects, tasks, or any other submodule, this feature lets you effortlessly integrate your essential documents, images, and files.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}},{"dedicated_theme_section_image":"","dedicated_theme_section_status":"on","dedicated_theme_section_heading":"Settings Page for Google Drive Integration","dedicated_theme_section_description":"<p>Streamline your tasks and boost productivity by seamlessly integrating Google Drive into Workdo-dash. Upload your Google Drive credential JSON, choose submodules to enable, and enjoy direct access to your documents and files. Say goodbye to unnecessary app switching and cumbersome downloads &ndash; effortlessly edit and view various file types right within the Workdo-dash interface.<\/p>","dedicated_theme_section_cards":{"1":{"title":null,"description":null}}}]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Google Drive"},{"screenshots":"","screenshots_heading":"Google Drive"},{"screenshots":"","screenshots_heading":"Google Drive"},{"screenshots":"","screenshots_heading":"Google Drive"},{"screenshots":"","screenshots_heading":"Google Drive"}]';
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
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', 'GoogleDrive')->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => 'GoogleDrive'

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
