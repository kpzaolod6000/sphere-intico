@php
    use Modules\GoogleDrive\Entities\GoogleDriveSetting;   
@endphp
@extends('layouts.main')

@section('page-title')
    {{ __('Google Drive') }}
@endsection

@section('page-breadcrumb')
    {{ __($module) }},{{ __('Google Drive') }}
@endsection

@section('page-action')
    <div>
        
        @if (module_is_active('GoogleDrive'))
            @can('googledrive manage')

                    @if(GoogleDriveSetting::is_folder_assigned($module))

                        <a class="btn btn-sm btn-primary m-1" data-ajax-popup="true" data-size="lg"
                            data-title="{{ __('Upload Files') }}" data-url="{{ route('upload.file.create',[$module,$folder_id]) }}" data-bs-toggle="tooltip"
                            data-bs-original-title="{{ __('Upload Files') }}">
                            <i class="ti ti-plus"></i>
                        </a>

                        <a target="_blank" href="{{ 'https://drive.google.com/drive/folders/'.$folder_id }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Open In Google Drive')}}" class="btn btn-sm btn-primary btn-icon m-1" >
                            <img src="{{ url('Modules/GoogleDrive/favicon.png') }}" width="15px" alt="">
                        </a>

                        <a href="{{ route('googledrive.module.index',[$module, $folder_id ,'grid']) }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Grid View')}}" class="btn btn-sm btn-primary btn-icon m-1">
                            <i class="ti ti-layout-grid"></i>
                        </a>

                    @endif

                    @if($folder_id != GoogleDriveSetting::get_folderId_by_name($module))    
                        <a href="{{ route('googledrive.module.index', [$module , $parent_folder_id ]) }}"  class="btn-submit btn btn-sm btn-primary"
                            data-toggle="tooltip" title="{{ __('Back') }}">
                            <i class=" ti ti-arrow-back-up"></i>
                        </a>
                    @else
                        <a href="{{ Session::get($module) }}"  class="btn-submit btn btn-sm btn-primary"
                            data-toggle="tooltip" title="{{ __('Back') }}">
                            <i class=" ti ti-arrow-back-up"></i>
                        </a>
                    @endif    
            @endcan
        @endif
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            
            @if(GoogleDriveSetting::is_folder_assigned($module) && company_setting('google_drive_token') != null && company_setting('google_drive_refresh_token') != null)
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table px-2 mb-0 py-3" id="assets">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Size') }}</th>
                                        <th>{{ __('LastModified') }}</th>
                                        @can('googledrive delete')
                                            <th>{{ __('Action') }}</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($google_drive_files))
                                        @foreach($google_drive_files as $file)
                                            <tr class="font-style">
                                                @if($file['mimeType'] == 'application/vnd.google-apps.folder')
                                                    <td><a href="{{ route('googledrive.module.index',[ $module ,$file['id']]) }}"><img height="35px" src="{{ url('Modules/GoogleDrive/Resources/assets/image/folder_icon.png') }}" alt="" class="p-2">{{  $file['name']}} </a></td>
                                                @else
                                                    <td><a target="_blank" href="{{ $file['webViewLink'] }}"><img src="{{ $file['iconLink'] }}" alt="" class="p-2">{{  $file['name']}} </a></td>
                                                @endif   
                                                <td>{{ round(($file->getSize() / 1024) / 1024, 2) . ' MB';   }}</td>
                                                <td>{{  $file['modifiedTime']}}</td>
                                                @can('googledrive delete')
                                                    <td>
                                                        {!! Form::open(['method' => 'GET', 'route' => ['file.delete', $file['id']],'id'=>'delete-form-'.$file['id']]) !!}

                                                        <a href="#" class="mx-3 btn btn-sm  bg-danger align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="{{'delete-form-'.$file['id']}}">
                                                        <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}

                                                    </td>
                                                @endcan
                                            </tr>    
                                        @endforeach
                                    @else    
                                        <tr class="p-5 m-5">
                                            <td class="text-center" colspan="4">{{ __('Data Not found!') }}</td>
                                        </tr>
                                    @endif    
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            @else       
                <div class="card">
                    <div class="card-header pb-3">
                        <i class="ti ti-info-circle pointer h2 text-primary"></i>
                        <span class="h4">{{ __('Info') }}</span>
                    </div>
                    <div class="card-body">
                        @if(company_setting('google_drive_token') == null || company_setting('google_drive_refresh_token') == null)
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-danger">{{ __('You have not authorized your google account to browse and attach folders. Click ') }} <a href="{{ route('auth.google') }}">{{ __('here') }}</a>{{ __(' to authorize.') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-auto">
                                    <p class="text-danger">{{ __('This record does not have folder assigned, Please choose a folder and click "Assign Folder "') }}</p>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                        data-title="{{ __('Choose Existing Folder') }}" data-url="{{ route('assign.folder',$module) }}" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('Choose Existing Folder') }}">
                                        <span>{{ __('Choose Existing Folder') }}</span>
                                    </a>
                                </div>
                                <div class="col-auto">
                                    <p class="text-danger">{{ __('OR') }}</p>
                                </div>
                                <div class="col-auto">
                                    <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                        data-title="{{ __('Create New Folder') }}" data-url="{{ route('create.new.folder',[$module,$folder_id]) }}" data-bs-toggle="tooltip"
                                        data-bs-original-title="{{ __('Create New Folder') }}">
                                        <span>{{ __('Create New Folder') }}</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    <div>
                </div>
            @endif    
        </div>
    </div>
@endsection
