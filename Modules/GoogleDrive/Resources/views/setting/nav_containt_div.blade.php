@can('googledrive manage')
@php
        $google_drive_modules = Modules\GoogleDrive\Entities\GoogleDriveSetting::get_modules();
@endphp
<div class="card" id="google-drive">
        {{ Form::open(array('route' => 'google.drive.setting.store','method' => 'post', 'enctype' => 'multipart/form-data')) }}
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col-10">
                    <h5 class="">{{ __('Google Drive Settings') }}</h5>
                </div>
                <div class=" text-end  col-auto">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                    {{Form::label('Google drive json file',__('Google Drive Json File'),['class'=>'col-form-label']) }}
                    <input type="file" class="form-control"  name="google_drive_json_file" id="google_drive_json_file" >
                </div>
            </div>
            
            <div class="row pt-5">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    @php
                        $active = 'active';
                    @endphp
                    @foreach ($google_drive_modules as $key => $e_module)
                        @if((module_is_active($key) ) || $key == 'General')
                            <li class="nav-item">
                                <a class="nav-link text-capitalize {{ $active }}" id="pills-{{ ($key) }}-tab-email" data-bs-toggle="pill" href="#pills-{{ ($key) }}-email" role="tab" aria-controls="pills-{{ ($key) }}-email" aria-selected="true">{{ Module_Alias_Name($key) }}</a>
                            </li>
                            @php
                                $active = '';
                            @endphp
                        @endif
                    @endforeach
                </ul>
                <div class="tab-content mb-3" id="pills-tabContent">
                    
                    @foreach ($google_drive_modules as $key => $e_module)
                        @if((module_is_active($key)) || $key == 'General')
                            <div class="tab-pane fade {{ $loop->index == 0? 'active':'' }} show" id="pills-{{ ($key) }}-email" role="tabpanel" aria-labelledby="pills-{{ ($key) }}-tab-email">
                                <div class="row">
                                    @foreach ($e_module as $sub_module)
                                    <div class="col-lg-3 col-md-4 col-6">
                                        <div class="d-flex align-items-center justify-content-between list_colume_notifi pb-2 mb-3">
                                            <div class="mb-3 mb-sm-0">
                                                <h6>
                                                    <label for="{{ $sub_module }}" class="form-label">{{ ($sub_module) }}</label>
                                                </h6>
                                            </div>
                                            <div class="text-end">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input type="hidden" name="google_drive[{{ $sub_module.'_drive' }}]" value="0" />
                                                    <input class="form-check-input" {{(company_setting($sub_module.'_drive') == true) ? 'checked' : ''}} id="google_drive" name="google_drive[{{ $sub_module.'_drive' }}]" type="checkbox" value="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button class="btn-submit btn btn-primary" type="submit">
                {{__('Save Changes')}}
            </button>
        </div>
        {{Form::close()}}
</div>

@endcan