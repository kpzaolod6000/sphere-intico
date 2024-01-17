@extends('layouts.main')
@section('page-title')
    {{ __('Sales Document Edit') }}
@endsection
@section('title')
    {{ __('Edit Document') }} {{ '(' . $salesdocument->name . ')' }}
@endsection
@push('css')
<link rel="stylesheet" href="{{ Module::asset('Sales:Resources/assets/css/custom.css') }}">
@endpush
@section('page-action')
    <div class="btn-group" role="group">
        @if (!empty($previous))
            <div class="action-btn  ms-2">
                <a href="{{ route('salesdocument.edit', $previous) }}" class="btn btn-sm btn-primary btn-icon m-1"
                    data-bs-toggle="tooltip" title="{{ __('Previous') }}">
                    <i class="ti ti-chevron-left"></i>
                </a>
            </div>
        @else
            <div class="action-btn  ms-2">
                <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip"
                    title="{{ __('Previous') }}">
                    <i class="ti ti-chevron-left"></i>
                </a>
            </div>
        @endif
        @if (!empty($next))
            <div class="action-btn  ms-2">
                <a href="{{ route('salesdocument.edit', $next) }}" class="btn btn-sm btn-primary btn-icon m-1"
                    data-bs-toggle="tooltip" title="{{ __('Next') }}">
                    <i class="ti ti-chevron-right"></i>
                </a>
            </div>
        @else
            <div class="action-btn  ms-2">
                <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip"
                    title="{{ __('Next') }}">
                    <i class="ti ti-chevron-right"></i>
                </a>
            </div>
        @endif
    </div>
@endsection
@section('page-breadcrumb')
    {{ __('Sales Document') }},
    {{ __('Edit') }}
@endsection
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1" class="list-group-item list-group-item-action border-0">{{ __('Overview') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-2" class="list-group-item list-group-item-action border-0">{{__('Sales Account')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="card">
                        {{Form::model($salesdocument,array('route' => array('salesdocument.update', $salesdocument->id), 'enctype'=>'multipart/form-data','method' => 'PUT')) }}
                        <div class="card-header">
                            <div class="float-end">
                                @if (module_is_active('AIAssistant'))
                                    @include('aiassistant::ai.generate_ai_btn',['template_module' => 'salesdocument','module'=>'Sales'])
                                @endif
                            </div>
                            <h5>{{ __('Overview') }}</h5>
                            <small class="text-muted">{{ __('Edit about your document information') }}</small>
                        </div>

                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
                                            @error('name')
                                                <span class="invalid-name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('folder', __('Folder'), ['class' => 'form-label']) }}
                                            {!! Form::select('folder', $folders, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            @error('folder')
                                                <span class="invalid-folder" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
                                            {!! Form::select('type', $type, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            @error('type')
                                                <span class="invalid-type" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                                            {!! Form::select('account', $account_name, null, ['class' => 'form-control']) !!}
                                            @error('account')
                                                <span class="invalid-account" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('opportunities', __('Opportunities'), ['class' => 'form-label']) }}
                                            {!! Form::select('opportunities', $opportunities, null, ['class' => 'form-control']) !!}
                                            @error('opportunities')
                                                <span class="invalid-opportunities" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('publish_date', __('Publish Date'), ['class' => 'form-label']) }}
                                            {!! Form::date('publish_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            @error('publish_date')
                                                <span class="invalid-publish_date" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('expiration_date', __('Expiration Date'), ['class' => 'form-label']) }}
                                            {!! Form::date('expiration_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            @error('expiration_date')
                                                <span class="invalid-expiration_date" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                            {!! Form::select('status', $status, null, ['class' => 'form-control', 'required' => 'required']) !!}
                                            @error('status')
                                                <span class="invalid-status" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-6 mb-3 field" data-name="attachments">
                                        <div class="attachment-upload">
                                            <div class="attachment-button">
                                                <div class="pull-left">
                                                    <div class="form-group">
                                                        {{ Form::label('attachment', __('Attachment'), ['class' => 'form-label']) }}
                                                        <input type="file"name="attachment" class="form-control mb-3" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                        <img id="blah" class="mt-2" src="{{ !empty($salesdocument->attachment) ? get_file($salesdocument->attachment):'' }}" style="width:25%;"/>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('description')
                                                <span class="invalid-description" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('user', __('Assigned User'), ['class' => 'form-label']) }}
                                            {!! Form::select('user', $user, $salesdocument->user_id, ['class' => 'form-control']) !!}
                                            @error('user')
                                                <span class="invalid-user" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                                            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Description')]) }}
                                            @error('description')
                                                <span class="invalid-description" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        {{ Form::submit(__('Update'), ['class' => 'btn-submit btn btn-primary']) }}
                                    </div>


                                </div>
                            </form>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="useradd-2" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Sales Account') }}</h5>
                                    <small class="text-muted">{{ __('Assigned account for this document') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a href="#" data-size="lg"
                                            data-url="{{ route('salesaccount.create', ['document', $salesdocument->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                            data-title="{{ __('Create New Account') }}"
                                            class="btn btn-sm btn-primary btn-icon-only ">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="budget">{{ __('Website') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Type') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Country') }}</th>
                                            @if (Gate::check('salesaccount show') || Gate::check('salesaccount edit') || Gate::check('saleaccount delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accounts as $account)
                                            <tr>
                                                <td>
                                                    <a href="#" data-size="md"
                                                        data-url="{{ route('salesaccount.show',$account->id) }}"
                                                        data-ajax-popup="true" data-title="{{ __('Account Details') }}"
                                                        class="action-item text-primary">
                                                        {{ $account->name }}
                                                    </a>
                                                </td>
                                                <td class="budget">
                                                    {{ $account->website }}
                                                </td>
                                                <td>
                                                    <span class="budget">
                                                        {{ !empty($account->AccountType) ? $account->AccountType->name : '' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="budget">{{ $account->shipping_city }}</span>
                                                </td>
                                                @if (Gate::check('salesaccount show') || Gate::check('salesaccount edit') || Gate::check('salesaccount delete'))
                                                    <td class="text-end">
                                                        @can('salesaccount show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="#" data-size="md"
                                                                    data-url="{{ route('salesaccount.show', $account->id) }}"
                                                                    data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                                    data-ajax-popup="true"
                                                                    data-title="{{ __('Account Details') }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('salesaccount edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('salesaccount.edit', $account->id) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-title="{{ __('Edit Account') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('salesaccount delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['salesaccount.destroy', $account->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm   align-items-center text-white show_confirm"
                                                                    data-bs-toggle="tooltip" title='Delete'>
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        @endcan
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
