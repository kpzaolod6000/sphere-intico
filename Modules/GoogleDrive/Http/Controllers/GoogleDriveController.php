<?php

namespace Modules\GoogleDrive\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GoogleDrive\Entities\GoogleDriveSetting;
use Nwidart\Modules\Facades\Module;
use Rawilk\Settings\Settings;
use Rawilk\Settings\Support\Context;
use Google\Client;
use Google\Service\Oauth2;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;


class GoogleDriveController extends Controller
{
    
    public function index(Request $request , $module , $folder_id='root', $view='')
    {
        session()->put($module, \URL::previous());
        if(\Auth::user()->can('googledrive manage')){
            if(company_setting($module.'_drive')){

                $google_drive_modules = GoogleDriveSetting::get();  
                $parent_folder_id ='';
                $google_drive_files = '';

                if((company_setting('google_drive_token') != null) && (company_setting('google_drive_refresh_token') != null) ){

                    $parent_folder_id =  GoogleDriveSetting::get_parent_folder_Id( $module , $folder_id );
                    $folder_id = GoogleDriveSetting::get_folderId_by_name($module);
                    $google_drive_files = $this->getfiles($folder_id);

                    // if($google_drive_files == false){
                    //     return redirect(route('auth.google'));
                    // }

                }
                if(!empty($view) && $view == 'grid'){
                    
                    return view('googledrive::layouts.google_drive_index_grid', compact('google_drive_modules','google_drive_files','parent_folder_id','folder_id','module'));                
                }else{
                    return view('googledrive::layouts.google_drive_index', compact('google_drive_modules','google_drive_files','parent_folder_id','folder_id','module'));
                }
            }

            return redirect()->back()->with('error',__('Permission Denied!'));
        }
        else{
            return redirect()->back()->with('error',__('Permission Denied!'));
        }
    }

    public function getmodulefiles(Request $request , $module , $folder_id='root', $view='')
    {
        if(\Auth::user()->can('googledrive manage')){
            if(company_setting($module.'_drive')){

                if(GoogleDriveSetting::is_folder_assigned())
                {
                    return redirect()->route('googledrive.index');
                }

                $google_drive_modules = GoogleDriveSetting::get();  
                $google_drive_files = $this->getfiles($folder_id);
                $parent_folder_id =  GoogleDriveSetting::get_parent_folder_Id( $module , $folder_id );
                // $parent_folder_id = $parent_folder['id'];
                // $parent_folder_name = $parent_folder['name'];

                if(!empty($view) && $view == 'grid'){
                    
                    return view('googledrive::layouts.google_drive_index_grid', compact('google_drive_modules','google_drive_files','parent_folder_id','folder_id','module'));
                
                }else{

                    return view('googledrive::layouts.google_drive_index', compact('google_drive_modules','google_drive_files','parent_folder_id','folder_id','module'));
                }
            }

            return redirect()->back()->with('error',__('permission Denied!'));

        }else{

            return redirect()->back()->with('error',__('permission Denied!'));
        }
            
    }

    public function getfiles($folder_id='')
    {
        try{
            if(!empty($folder_id)){

                $query = "'$folder_id' in parents";

            }else{

                $query = "'root' in parents";
            }

            $drive = $this->get_drive();

            if($drive){

                $files = $this->get_drive()->files->listFiles(['fields' => '*','q' => $query]);

            }else{

                return false;
            }
            return  $files->getFiles();
        }
        catch (\Exception $e)
        {
            return false;
            // return $error = $e->getMessage();
        }
    }

    // Get new access token
    public function getNewAccessToken() 
    {
        try{
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_drive_json_file')));
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken(company_setting('google_drive_refresh_token'));

            $userContext = new Context(['user_id' => \Auth::user()->id, 'workspace_id' => getActiveWorkSpace()]);
            $userContext1 = new Context(['user_id' => \Auth::user()->id, 'workspace_id' => getActiveWorkSpace()]);

            \Settings::context($userContext)->set('google_drive_token', $newAccessToken);
            \Settings::context($userContext1)->set('google_drive_refresh_token', $newAccessToken['refresh_token']);
            
            return  true;

        } catch (\Exception $e) {

            return false;
        }
    }

    // check access token
    public function isGoogleTokenExpired() 
    {

        $googleClient = new Client();
        $googleClient->setAccessToken(company_setting('google_drive_token'));
    
        if ($googleClient->isAccessTokenExpired()) {
            return true; // Token has expired
        }
        return false; // Token is still valid
    }

    // Get Google Drive with Client
    public function get_drive()
    {
        try{
            // Create a new Google Client instance
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_drive_json_file')));
            if($this->isGoogleTokenExpired())
            {
                if($this->getNewAccessToken()){
                    
                    $client->setAccessToken(company_setting('google_drive_token')); // Set the user's access token
                }

            }else{

                $client->setAccessToken(company_setting('google_drive_token')); // Set the user's access token
            }

            return  new Drive($client);

        } catch (\Exception $e) {

            return false;
        }
    }

    // Authenticate with google
    public function redirectToGoogle()
    {
        session()->put('google_auth_back_url' , \URL::previous());

        try {
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_drive_json_file')));
            $client->setRedirectUri(route('auth.google.callback'));
            $client->addScope('https://www.googleapis.com/auth/drive');
            $client->setAccessType('offline');

            return redirect($client->createAuthUrl());

        } catch (\Exception $e) {
            return redirect(\Session::get('google_auth_back_url'))->with('error',__('Something Went Wrong!'));
        }
    }

    // Authenticate with google callback functon
    public function handleGoogleCallback(Request $request)
    {
        try {
            $client = new Client();
            $client->setAuthConfig(base_path(company_setting('google_drive_json_file')));
            $client->setRedirectUri(route('auth.google.callback'));

            $token = $client->fetchAccessTokenWithAuthCode($request->code);
            $userContext = new Context(['user_id' => \Auth::user()->id, 'workspace_id' => getActiveWorkSpace()]);

            \Settings::context($userContext)->set('google_drive_token', $token);

            if(isset($token['refresh_token'])){

                $userContext1 = new Context(['user_id' => \Auth::user()->id, 'workspace_id' => getActiveWorkSpace()]);
                \Settings::context($userContext1)->set('google_drive_refresh_token', $token['refresh_token']);
            }

            return redirect(\Session::get('google_auth_back_url')); // Redirect to dashboard after successful login

        } catch (\Exception $e) {
            
            return redirect(\Session::get('google_auth_back_url'))->with('error', __('Something Went Wrong!'));

        }
    }

    // Assign folder to the sub module
    public function assign_folder($module='')
    {
        $parent_module = GoogleDriveSetting::parent_module($module);
        $google_drive_modules = GoogleDriveSetting::where('module',$parent_module)->get();

        $files = $this->get_drive()->files->listFiles(['fields' => '*']);
        $google_drive_files = $files->getFiles();
        
        return view('googledrive::folders.assign_folder',compact('google_drive_modules','google_drive_files','module'));
    }

    // Assign folder to the sub module
    public function assign_folder_store(Request $request , $module='')
    {
        GoogleDriveSetting::assign_folder_to_module($module , $request->parent_id);
        return redirect()->route('googledrive.module.index',[$module,$request->parent_id]);
    }

    // Create New folder in google Drive will be assigned automatically to the sub module
    public function create_folder($module , $folder_id='')
    {
        return view('googledrive::folders.create',compact('folder_id','module'));
    }

    // Create New folder in google Drive will be assigned automatically to the sub module
    public function store_folder(Request $request, $module , $folder_id='')
    {
        $createdFolder = GoogleDriveSetting::create_new_drive_folder($request->folder_name ,$module , $folder_id );
        return redirect()->back()->with('success',__('Folder Created Successfully'));
    }

    // Store sub module settings
    public function GoogleDriveSettingsStore(Request $request)
    {
        $userContext = new Context(['user_id' => \Auth::user()->id,'workspace_id'=>getActiveWorkSpace()]);

        if($request->has('google_drive'))
        {
            foreach($request->google_drive as $key => $value)
            {
                \Settings::context($userContext)->set($key, $value);
            }
        }

        if($request->has('google_drive_json_file'))
        {
            $google_drive_json_file = time()."-google_drive_json_file." . $request->google_drive_json_file->getClientOriginalExtension();
            $path = upload_file($request,'google_drive_json_file',$google_drive_json_file,'google_drive_json',[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            
            // old img delete
            if(!empty(company_setting('google_drive_json_file')) && strpos(company_setting('google_drive_json_file'),'avatar.png') == false && check_file(company_setting('google_drive_json_file')))
            {
                delete_file(company_setting('google_drive_json_file'));
            }

            \Settings::context($userContext)->set('google_drive_json_file', $path['url']);
            \Settings::context($userContext)->set('google_drive_token', '');
            \Settings::context($userContext)->set('google_drive_refresh_token', '');

        }

        return redirect()->back()->with('success','Google Drive Setting saved sucessfully.');
    }

    public function uploadfiles_create($module)
    {
        $folder_id = GoogleDriveSetting::get_folderId_by_name($module);
        return view('googledrive::folders.addfiles',compact('module','folder_id'));
    }

    public function uploadfiles_store(Request $request, $module)
    {
        if($request->hasFile('file')) 
        {
            $file = $request->file('file');
            $filePath = $file->getPathname();
            $filename = $request->file('file')->getClientOriginalName();
            $FolderId = GoogleDriveSetting::get_folderId_by_name($module);
            $mimetype = GoogleDriveSetting::get_mimetype(mime_content_type($filePath));

            $fileMetadata = new DriveFile([
                'name' => $filename,
                'mimeType' => $mimetype,
                'parents' => [$FolderId], // Set the parent folder ID
            ]);

            $x = GoogleDriveSetting::upload_file($fileMetadata , $filePath);
            return $res = [
                'flag' => 1,
                'msg'  =>'File Uploaded Successfully',
            ];

        } else {

            return $res = [
                'flag' => 2,
                'msg'  =>'Something went wrong!',
            ];
        }    
    }

    public function delete_file($folderId)
    {
        $this->get_drive()->files->delete($folderId);
        return redirect()->back()->with('success',__('File Deleted Successfully!'));
    }

}
