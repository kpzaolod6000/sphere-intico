<?php

namespace Modules\GoogleDrive\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;


class GoogleDriveSetting extends Model
{
    use HasFactory;

    protected $table = 'google_drive_settings';

    protected $fillable = [
        'module',
        'status',
        'workspace_id',
        'type',
        'value',
        'name',
    ];
    
    protected static function newFactory()
    {
        return \Modules\GoogleDrive\Database\factories\GoogleDriveSettingFactory::new();
    }

    // Get parent Module
    public static function parent_module($module='')
    {
        $drive_module = GoogleDriveSetting::where('name',$module)->first();
        return $drive_module->module;
    }

    //Get All modules from DB has file upload
    public static function get_modules()
    {
        $settings_modules = GoogleDriveSetting::get();

        $data = [];
        foreach($settings_modules as $key => $module){

            $sub_modules = GoogleDriveSetting::where('module',$module->module)->pluck('name');
            $data[$module->module]  = $sub_modules;
        }

        return $data ;
    }

    // Check if folder is assigned to the sub module
    public static function is_folder_assigned($module = '' , $record_id = '')
    {
        $google_drive_modules = GoogleDriveSetting::where('workspace_id' , getActiveWorkSpace())->get();

        foreach ($google_drive_modules as $row) {
            $data[$row->name] = $row->value;
        }

        if(isset($data[$module]) || !empty($data[$module]) )
        {
            $folder = json_decode($data[$module]);

            if (!isset($folder[0]->$module) || empty($folder[0]->$module)) {

                return false;

            }else{

                return true;
            }
        }else{

            return false;

        }
    }

    // Get assigned folder Id form sub module
    public static function get_folderId_by_name($module = '' , $record_id = '')
    {
        $google_drive_modules = GoogleDriveSetting::where('name',$module)->where('workspace_id' , getActiveWorkSpace())->first();

        if(isset($google_drive_modules->value) && !empty($google_drive_modules->value) && $google_drive_modules->value != '')
        {
            $folder = json_decode($google_drive_modules->value);

            if (!isset($folder[0]->$module) || empty($folder[0]->$module)) {

                return '';
            }else{

                return $folder[0]->$module;
            }
        }else{

            return '';
        }
    }

    public static function assign_folder_to_module($module = '' , $folder_id , $record_id = '')
    {
        $datas[$module]= $folder_id;
        $data[] = $datas;
        $data = json_encode($data);

        GoogleDriveSetting::updateOrCreate(['name' =>  $module ,'workspace_id' => getActiveWorkSpace()],['value' => $data]);
    }

    // get Parent folder of the Google Drive by module
    public static function get_parent_folder_Id($module,$folder_id)
    {
        try
        {
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_drive_json_file')));
            $client->setAccessToken(company_setting('google_drive_token')); // Set the user's access token

            $drive =  new Drive($client);
            $folder = $drive->files->get($folder_id, ['fields' => 'id,name, parents']);
            // $x['id'] = $folder->getParents()[0]; 
            // $x['name'] = $folder->name; 
            return  $folder->getParents()[0]; 
        }
        catch (\Exception $e)
        {
            return false;
        }

    }

    // Get Mimetype of the file 
    public static function get_mimetype($type='')
    {
        $mimeTypesToConvert = [
            'application/msword' => 'application/vnd.google-apps.document', // Word Document (DOC)
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'application/vnd.google-apps.document', // Word Document (DOCX)
            'application/vnd.ms-excel' => 'application/vnd.google-apps.spreadsheet', // Excel Spreadsheet (XLS)
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'application/vnd.google-apps.spreadsheet', // Excel Spreadsheet (XLSX)
            'application/vnd.ms-powerpoint' => 'application/vnd.google-apps.presentation', // PowerPoint Presentation (PPT)
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'application/vnd.google-apps.presentation', // PowerPoint Presentation (PPTX)
            'text/plain' => 'application/vnd.google-apps.document', // Plain Text
            'text/csv' => 'application/vnd.google-apps.spreadsheet', // CSV File
            // 'image/jpeg' => 'application/vnd.google-apps.photo', // JPEG Image
            // 'image/png' => 'application/vnd.google-apps.photo', // PNG Image
            'application/pdf' => 'application/vnd.google-apps.document', // PDF Document
            // 'application/zip' => 'application/vnd.google-apps.document', // ZIP Archive
            // Add more mappings as needed
        ];

        $data = [];
        foreach($mimeTypesToConvert as $key => $value)
        {
            $data[$key]  = $value;
        }

        if(isset($data[$type]))
        {
            return $data[$type];

        }else{

            return $type;
        }        
    }

    // Upload the file 
    public static function upload_file($fileMetadata, $filePath='')
    {
        GoogleDriveSetting::get_mimetype(mime_content_type($filePath));
        $client = new Client();
        $client->setAuthConfig(base_path(company_setting('google_drive_json_file')));
        $client->setAccessToken(company_setting('google_drive_token')); // Set the user's access token

        $drive =  new Drive($client);

        return $drive->files->create($fileMetadata, [
            'data' => file_get_contents($filePath),
            'uploadType' => 'multipart',
            'mimeType' => mime_content_type($filePath) , // Set the correct MIME type dynamically
        ]);

    }

    // Create New folder
    public static function create_new_drive_folder($folder_name ,$module='', $folder_id='')
    {
        $parentFolderId = !empty($folder_id) ? $folder_id : 'root' ;

        // Create metadata for the new folder
        $folderMetadata = new DriveFile([
            'name' => $folder_name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentFolderId], // Set the parent folder ID
        ]);

        $client = new Client();
        $client->setAuthConfig(base_path(company_setting('google_drive_json_file')));
        $client->setAccessToken(company_setting('google_drive_token')); // Set the user's access token

        $drive =  new Drive($client);
        $createdFolder = $drive->files->create($folderMetadata);

        if(!empty($module) &&  $module != ''){

            GoogleDriveSetting::assign_folder_to_module($module , $createdFolder->id);
        }

        return $createdFolder;
    }

    //get views data via submodule name
    public static function get_view_to_stack_hook()
    {

        $views = [
            'Account'            => 'sales::salesaccount.index',
            'Accounts'           => 'account::bankAccount.index',
            'Assets'             => 'assets::index',
            'Bill'               => 'account::bill.index',
            'Bug'                => 'taskly::projects.bug_report',
            'Cases'              => 'sales::commoncase.index',
            'Contracts'          => 'contract::contracts.index',
            'Customer'           => 'account::customer.index',
            'Deal'               => 'lead::deals.index',
            'Document'           => 'hrm::document.index',
            'Documents'          => 'aidocument::document.index',
            'Employee'           => 'hrm::employee.index',
            'Event'              => 'hrm::event.index',
            'Generated Image'    => 'aiimage::image.index',
            'Interview Schedule' => 'recruitment::interviewSchedule.index',
            'Invoice'            => 'invoice.index',
            'Job Application'    => 'recruitment::jobApplication.index',
            'Jobs'               => 'recruitment::job.index',
            'Knowledge'          => 'supportticket::knowledge.index',
            'Lead'               => 'lead::leads.index',
            'Leave'              => 'hrm::leave.index',
            'Meeting'            => 'sales::meeting.index',
            'Notes'              => 'notes::index',
            'Opportunities'      => 'sales::opportunities.index',
            'Payslip'            => 'hrm::payslip.index',
            'POS Order'          => 'pos::pos.report',
            'Products'           => 'productservice::index',
            'Projects'           => 'taskly::projects.show',
            'Proposal '          => 'proposal.index',
            'Purchase'           => 'pos::purchase.index',
            'Retainers'          => 'retainer::retainer.index',
            'Revenue'            => 'account::revenue.index',
            'Sales Document'     => 'sales::document.index',
            'Sales Invoice'      => 'sales::salesinvoice.index',
            'Task'               => 'taskly::projects.taskboard',
            'Tickets'            => 'supportticket::ticket.index',
            'Transaction'        => 'account::transaction.index',
            'Vender'             => 'account::vendor.index',
            'Warehouse'          => 'pos::warehouse.index',
            
        ];

        return $views;
    }

}
