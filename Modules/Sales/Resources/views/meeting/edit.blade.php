@extends('layouts.main')
@section('page-title')
    {{__('Meeting Edit')}}
@endsection
@section('title')
    {{__('Edit Meeting')}}
@endsection
@section('page-action')
    <div class="btn-group" role="group">
        @if(!empty($previous))
        <div class="action-btn  ms-2">
            <a href="{{ route('meeting.edit',$previous) }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" title="{{__('Previous')}}">
                <i class="ti ti-chevron-left"></i>
            </a>
        </div>
        @else
        <div class="action-btn  ms-2">
            <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip" title="{{__('Previous')}}">
                <i class="ti ti-chevron-left"></i>
            </a>
        </div>
        @endif
        @if(!empty($next))
        <div class="action-btn  ms-2">
            <a href="{{ route('meeting.edit',$next) }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" title="{{__('Next')}}">
                <i class="ti ti-chevron-right"></i>
            </a>
        </div>
        @else
        <div class="action-btn  ms-2">
            <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip" title="{{__('Next')}}">
                <i class="ti ti-chevron-right"></i>
            </a>
        </div>
        @endif
    </div>
@endsection
@section('page-breadcrumb')
   {{__('Meeting')}},
   {{__('Edit')}}
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#useradd-1" class="list-group-item list-group-item-action">{{ __('Overview') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div id="useradd-1" class="card">
                    {{Form::model($meeting,array('route' => array('meeting.update', $meeting->id), 'method' => 'PUT')) }}
                    <div class="card-header">
                        <div class="float-end">
                            @if (module_is_active('AIAssistant'))
                                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'meeting','module'=>'Sales'])
                            @endif
                        </div>
                        <h5>{{ __('Overview') }}</h5>
                        <small class="text-muted">{{__('Edit about your meeting information')}}</small>
                    </div>

                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                                        {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                                        @error('name')
                                        <span class="invalid-name" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                    {{Form::label('status',__('Status'),['class'=>'form-label']) }}
                                    {!!Form::select('status', $status, null,array('class' => 'form-control','required'=>'required')) !!}
                                    @error('status')
                                    <span class="invalid-status" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                    {{Form::label('start_date',__('Start Date'),['class'=>'form-label']) }}
                                    {!!Form::date('start_date', null,array('class' => 'form-control','required'=>'required')) !!}
                                    @error('start_date')
                                    <span class="invalid-start_date" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{Form::label('end_date',__('End Date'),['class'=>'form-label']) }}
                                        {!!Form::date('end_date', null,array('class' => 'form-control','required'=>'required')) !!}
                                        @error('end_date')
                                        <span class="invalid-end_date" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        {{Form::label('description',__('Description'),['class'=>'form-label']) }}
                                        {{Form::textarea('description',null,array('class'=>'form-control','rows'=>2,'placeholder'=>__('Enter Description')))}}
                                        @error('description')
                                        <span class="invalid-description" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                    {{Form::label('account',__('Account'),['class'=>'form-label']) }}
                                    {!! Form::select('account', $account_name, null,array('class' => 'form-control')) !!}
                                    </div>
                                    @error('account')
                                    <span class="invalid-account" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        {{Form::label('user_id',__('Assigned User'),['class'=>'form-label']) }}
                                        {!! Form::select('user_id', $user, null,array('class' => 'form-control','required'=>'required')) !!}
                                        @error('user')
                                        <span class="invalid-user" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <hr class="mt-2 mb-2">
                                    <h5>{{__('Attendees')}}</h5>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{Form::label('attendees_user',__('User'),['class'=>'form-label']) }}
                                        {!! Form::select('attendees_user', $user, null,array('class' => 'form-control')) !!}
                                        @error('attendees_user')
                                        <span class="invalid-attendees_user" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{Form::label('attendees_contact',__('Contact'),['class'=>'form-label']) }}
                                        {!! Form::select('attendees_contact', $attendees_contact, null,array('class' => 'form-control')) !!}
                                        @error('attendees_contact')
                                        <span class="invalid-attendees_contact" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {{Form::label('attendees_lead',__('Lead'),['class'=>'form-label']) }}
                                        {!! Form::select('attendees_lead', $attendees_lead, null,array('class' => 'form-control')) !!}
                                        @error('attendees_lead')
                                        <span class="invalid-attendees_lead" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-end">
                                    {{Form::submit(__('Update'),array('class'=>'btn-submit btn btn-primary'))}}
                                </div>


                            </div>
                        </form>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>



@endsection
@push('scripts')
<script>
    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: '#useradd-sidenav',
        offset: 300
    })
</script>
@endpush
