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

<style>
    .text-container {
        position: relative;
        overflow: hidden;
        max-width: 100%;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    
    .text-container::after {
        content: attr(data-text);
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: white; /* Set the background color to match your background */
        padding: 0 4px;
        white-space: nowrap;
        overflow: hidden;
    }
    
    .text-container:hover::after {
        content: none;
    }
    
    .text-container:hover {
        white-space: normal;
        overflow: visible;
    }
    
    .wrap-effect:hover {
        white-space: normal;
        overflow: visible;
    }

</style>

@section('page-action')
    <div>
        @if(module_is_active('GoogleDrive'))
            @can('googledrive manage')
                @if(GoogleDriveSetting::is_folder_assigned($module))
                    <a class="btn btn-sm btn-primary p-2 m-1" data-ajax-popup="true" data-size="lg"
                        data-title="{{ __('Upload Files') }}" data-url="{{ route('upload.file.create',[$module,$folder_id]) }}" data-bs-toggle="tooltip"
                        data-bs-original-title="{{ __('Upload Files') }}">
                        <i class="ti ti-plus"></i>
                    </a>
                    <a target="_blank" href="{{ 'https://drive.google.com/drive/folders/'.$folder_id }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Open In Google Drive')}}" class="btn btn-sm btn-primary btn-icon p-2 m-1" >
                        <img src="{{ url('Modules/GoogleDrive/favicon.png') }}" width="15px" alt="">
                    </a>
                    <a href="{{ route('googledrive.module.index',[$module ,$folder_id]) }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('List View')}}" class="btn btn-sm btn-primary btn-icon p-2 m-1">
                        <i class="ti ti-list"></i>
                    </a>
                @endif

                @if($folder_id != GoogleDriveSetting::get_folderId_by_name($module))    
                    <a href="{{ route('googledrive.module.index', [$module , $parent_folder_id ,'grid']) }}"  class="btn-submit btn btn-sm p-2 btn-primary"
                        data-toggle="tooltip" title="{{ __('Back') }}">
                        <i class=" ti ti-arrow-back-up"></i>
                    </a>
                @else
                    <a href="{{ Session::get($module) }}"  class="btn-submit btn btn-sm p-2 btn-primary"
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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if (!empty($google_drive_files))
                            <div class="row">
                                @foreach ($google_drive_files as $file)
                                    <div class="col-xl-1 col-lg-1 col-md-2 col-sm-3 col-4" id="{{ 'file-'.$file['id'] }}">
                                        <div class="custom-card mb-3">
                                            <div class="custom-card-body text-center">
                                                @if ($file['mimeType'] == 'application/vnd.google-apps.folder')
                                                    <a href="{{ route('googledrive.module.index', [$module, $file['id'], 'grid']) }}">
                                                        <img src="{{ url('Modules/GoogleDrive/Resources/assets/image/folder_icon.png') }}" alt="" class="img-fluid">
                                                        <span class="d-block mt-2 text-container">{{ $file['name'] }}</span>
                                                    </a>
                                                @else
                                                    <a target="_blank" href="{{ $file['webViewLink'] }}">
                                                        <img src="{{ isset($file['thumbnailLink']) ? ($file['thumbnailLink']) : $file['iconLink'] }}" class="img-fluid">
                                                        <span class="d-block mt-2 text-container">{{ $file['name'] }}</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="row">
                                <div class="col">
                                    <div class="alert alert-info text-center my-5" role="alert">
                                        {{ __('Data Not found!') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const textContainers = document.querySelectorAll(".text-container");
        textContainers.forEach(function(container) {
        if (container.offsetHeight < container.scrollHeight) {
            container.classList.add("wrap-effect");
        }
        });
    });
</script>