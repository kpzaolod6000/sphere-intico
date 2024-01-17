@extends('layouts.main')
@section('page-title')
    {{ __('Sales Account Edit') }}
@endsection
@section('title')
    <div class="page-header-title">
        <h4 class="m-b-10">{{ __('Edit Account') }} {{ '(' . $salesaccount->name . ')' }}</h4>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ Module::asset('Sales:Resources/assets/css/custom.css') }}">
@endpush
@section('page-action')
    <div class="btn-group" role="group">
        @if (!empty($previous))
            <div class="action-btn  ms-2">
                <a href="{{ route('salesaccount.edit', $previous) }}" class="btn btn-sm btn-primary btn-icon m-1"
                    data-bs-toggle="tooltip" title="{{ __('Previous') }}">
                    <i class="ti ti-chevron-left"></i>
                </a>
            </div>
        @else
            <div class="action-btn ms-2">
                <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip"
                    title="{{ __('Previous') }}">
                    <i class="ti ti-chevron-left"></i>
                </a>
            </div>
        @endif
        @if (!empty($next))
            <div class="action-btn ms-2">
                <a href="{{ route('salesaccount.edit', $next) }}" class="btn btn-sm btn-primary btn-icon m-1"
                    data-bs-toggle="tooltip" title="{{ __('Next') }}">
                    <i class="ti ti-chevron-right"></i>
                </a>
            </div>
        @else
            <div class="action-btn ms-2">
                <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip"
                    title="{{ __('Next') }}">
                    <i class="ti ti-chevron-right"></i>
                </a>
            </div>
        @endif
    </div>
@endsection
@section('page-breadcrumb')
    {{ __('Sales Account') }},
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
                            <a href="#useradd-1"
                                class="list-group-item list-group-item-action border-0">{{ __('Overview') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-2"
                                class="list-group-item list-group-item-action border-0">{{ __('Stream') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-3"
                                class="list-group-item list-group-item-action border-0">{{ __('Contacts') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-4"
                                class="list-group-item list-group-item-action border-0">{{ __('Opportunities') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-5"
                                class="list-group-item list-group-item-action border-0">{{ __('Cases') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-6"
                                class="list-group-item list-group-item-action border-0">{{ __('Sales Documents') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-7"
                                class="list-group-item list-group-item-action border-0">{{ __('Quotes') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-8"
                                class="list-group-item list-group-item-action border-0">{{ __('Sales Orders') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-9"
                                class="list-group-item list-group-item-action border-0">{{ __('Sales Invoices') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-10"
                                class="list-group-item list-group-item-action border-0">{{ __('Calls') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-11"
                                class="list-group-item list-group-item-action border-0">{{ __('Meetings') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="card">
                        {{ Form::model($salesaccount, ['route' => ['salesaccount.update', $salesaccount->id], 'method' => 'PUT']) }}
                        <div class="card-header">
                            <div class="float-end">
                                @if (module_is_active('AIAssistant'))
                                    @include('aiassistant::ai.generate_ai_btn', [
                                        'template_module' => 'account',
                                        'module' => 'Sales',
                                    ])
                                @endif
                            </div>
                            <h5>{{ __('Overview') }}</h5>
                            <small class="text-muted">{{ __('Edit details about your account information') }}</small>
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
                                            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                                            {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter email'), 'required' => 'required']) }}
                                            @error('email')
                                                <span class="invalid-email" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('phone', __('Phone'), ['class' => 'form-label']) }}
                                            {{ Form::text('phone', null, ['class' => 'form-control', 'placeholder' => __('Enter phone'), 'required' => 'required']) }}
                                            @error('phone')
                                                <span class="invalid-phone" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('website', __('Website'), ['class' => 'form-label']) }}
                                            {{ Form::text('website', null, ['class' => 'form-control', 'placeholder' => __('Enter Website'), 'required' => 'required']) }}
                                            @error('website')
                                                <span class="invalid-website" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('billing_address', __('Billing Address')), ['class' => 'form-label'] }}
                                            <div class="action-btn bg-primary ms-2 float-end">
                                                <a class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                    id="billing_data" data-bs-toggle="tooltip" data-placement="top"
                                                    title="Same As Billing Address"><i class="ti ti-copy"></i></a>
                                            </div>
                                            <span class="clearfix"></span>
                                            {{ Form::text('billing_address', null, ['class' => 'form-control', 'placeholder' => __('Enter Billing Address'), 'required' => 'required']) }}
                                            @error('billing_address')
                                                <span class="invalid-billing_address" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('shipping_address', __('Shipping Address'), ['class' => 'form-label']) }}
                                            {{ Form::text('shipping_address', null, ['class' => 'form-control', 'placeholder' => __('Enter Shipping Address'), 'required' => 'required']) }}
                                            @error('shipping_address')
                                                <span class="invalid-shipping_address" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                            {{ Form::text('billing_city', null, ['class' => 'form-control', 'placeholder' => __('Enter Billing City'), 'required' => 'required']) }}
                                            @error('billing_city')
                                                <span class="invalid-billing_city" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                                            {{ Form::text('billing_state', null, ['class' => 'form-control', 'placeholder' => __('Enter Billing State'), 'required' => 'required']) }}
                                            @error('billing_state')
                                                <span class="invalid-billing_state" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
                                            {{ Form::text('shipping_city', null, ['class' => 'form-control', 'placeholder' => __('Enter Shipping City'), 'required' => 'required']) }}
                                            @error('shipping_city')
                                                <span class="invalid-shipping_city" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('state', __('State'), ['class' => 'form-label']) }}
                                            {{ Form::text('shipping_state', null, ['class' => 'form-control', 'placeholder' => __('Enter Shipping State'), 'required' => 'required']) }}
                                            @error('shipping_state')
                                                <span class="invalid-shipping_state" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('billing_country', __('Country'), ['class' => 'form-label']) }}
                                            {{ Form::text('billing_country', null, ['class' => 'form-control', 'placeholder' => __('Enter Billing country'), 'required' => 'required']) }}
                                            @error('billing_country')
                                                <span class="invalid-billing_country" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('billing_country', __('Postal Code'), ['class' => 'form-label']) }}
                                            {{ Form::number('billing_postalcode', null, ['class' => 'form-control', 'placeholder' => __('Enter Billing Postal Code'), 'required' => 'required']) }}
                                            @error('billing_postalcode')
                                                <span class="invalid-billing_postalcode" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('billing_country', __('Country'), ['class' => 'form-label']) }}
                                            {{ Form::text('shipping_country', null, ['class' => 'form-control', 'placeholder' => __('Enter Shipping Country'), 'required' => 'required']) }}
                                            @error('shipping_country')
                                                <span class="invalid-shipping_country" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{ Form::label('shipping_postalcode', __('Postal Code'), ['class' => 'form-label']) }}
                                            {{ Form::number('shipping_postalcode', null, ['class' => 'form-control', 'placeholder' => __('Enter Shipping Postal Code'), 'required' => 'required']) }}
                                            @error('shipping_postalcode')
                                                <span class="invalid-shipping_postalcode" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <hr class="mt-1 mb-2">
                                        <h6>{{ __('Detail') }}</h6>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
                                            {!! Form::select('type', $accountype, null, ['class' => 'form-control']) !!}
                                            @error('type')
                                                <span class="invalid-name" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{ Form::label('industry', __('Industry'), ['class' => 'form-label']) }}
                                            {!! Form::select('industry', $industry, null, ['class' => 'form-control']) !!}
                                            @error('industry')
                                                <span class="invalid-industry" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            {{ Form::label('document_id', __('Document')) }}
                                            {!! Form::select('document_id', $document_id, null, ['class' => 'form-control']) !!}
                                            @error('industry')
                                                <span class="invalid-industry" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                                            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
                                        </div>
                                    </div>


                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('user', __('Assigned User'), ['class' => 'form-label']) }}
                                            {!! Form::select('user', $user, $salesaccount->user_id, ['class' => 'form-control']) !!}
                                            @error('user')
                                                <span class="invalid-user" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        {{ Form::submit(__('Save'), ['class' => 'btn-submit btn btn-primary']) }}
                                    </div>


                                </div>
                            </form>
                        </div>
                        {{ Form::close() }}
                    </div>

                    <div id="useradd-2" class="card">
                        {{ Form::open(['route' => ['streamstore', ['account', $salesaccount->name, $salesaccount->id]], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-header">
                            <h5>{{ __('Stream') }}</h5>
                            <small class="text-muted">{{ __('Add stream comment') }}</small>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label('stream', __('Stream'), ['class' => 'form-label']) }}
                                            {{ Form::text('stream_comment', null, ['class' => 'form-control', 'placeholder' => __('Enter Stream Comment'), 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <input type="hidden" name="log_type" value="account comment">
                                    <div class="col-12 field" data-name="attachments">
                                        <div class="form-group">
                                            {{ Form::label('attachment', __('Attachment'), ['class' => 'form-label']) }}
                                            <input type="file"name="attachment" class="form-control mb-2"
                                                onchange="document.getElementById('attachment').src = window.URL.createObjectURL(this.files[0])">
                                            <img id="attachment" width="20%" height="20%" />
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        {{ Form::submit(__('Save'), ['class' => 'btn-submit btn btn-primary']) }}
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-12">
                            <div class="card-header">
                                <h5>{{ __('Latest comments') }}</h5>
                            </div>
                            @foreach ($streams as $stream)
                                @php
                                    $remark = json_decode($stream->remark);
                                @endphp
                                @if ($remark->data_id == $salesaccount->id)
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <ul class="list-group">
                                                <li class="list-group-item border-0 d-flex align-items-start">
                                                    <div class="avatar col-1">
                                                        <a href="{{ !empty($stream->file_upload) ? get_file($stream->file_upload) : get_file('uploads/users-avatar/avatar.png') }}"
                                                            target="_blank">

                                                            <img src="{{ !empty($stream->file_upload) ? get_file($stream->file_upload) : get_file('uploads/users-avatar/avatar.png') }}"
                                                                class="user-image-hr-prj ui-w-30 rounded-circle"
                                                                width="50px" height="50px">
                                                        </a>

                                                    </div>
                                                    <div class="d-block d-sm-flex align-items-center right-side col-11">
                                                        <div
                                                            class="col-10 d-flex align-items-start flex-column justify-content-center mb-sm-0">
                                                            <div class="h6 mb-1">{{ $remark->user_name }}
                                                            </div>
                                                            <span class="text-sm lh-140 mb-0">
                                                                posted to <a href="#">{{ $remark->title }}</a> ,
                                                                {{ $stream->log_type }} <a
                                                                    href="#">{{ $remark->stream_comment }}</a>
                                                            </span>
                                                        </div>
                                                        <div class="col-2 ms-2  d-flex align-items-center ">
                                                            <small class="float-end ">{{ $stream->created_at }}</small>
                                                        </div>
                                                    </div>

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>


                        {{ Form::close() }}
                    </div>

                    <div id="useradd-3" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Contacts') }}</h5>
                                    <small class="text-muted">{{ __('Assigned contacts for this account') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('contact.create', ['account', $salesaccount->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip"title="{{ __('Create') }}"
                                            data-title="{{ __('Create New Contact') }}" class="btn btn-sm btn-primary ">
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
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="budget">{{ __('Email') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Phone') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('City') }}</th>
                                            @if (Gate::check('contact show') || Gate::check('contact edit') || Gate::check('contact delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contacts as $contact)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('contact.edit', $contact->id) }}"
                                                        data-title="{{ __('Contact Details') }}" data-size="md"
                                                        class="action-item text-primary">
                                                        {{ $contact->name }}
                                                    </a>
                                                </td>
                                                <td class="budget">
                                                    {{ $contact->email }}
                                                </td>
                                                <td>
                                                    <span class="budget">
                                                        {{ $contact->phone }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="budget">{{ $contact->contact_city }}</span>
                                                </td>
                                                @if (Gate::check('contact show') || Gate::check('contact edit') || Gate::check('contact delete'))
                                                    <td class="text-end">
                                                        @can('contact show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a data-size="md"
                                                                    data-url="{{ route('contact.show', $contact->id) }}"
                                                                    data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                                    data-ajax-popup="true"
                                                                    data-title="{{ __('Contact Details') }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('contact edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('contact.edit', $contact->id) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-title="{{ __('Contact Edit') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('contact delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['contact.destroy', $contact->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm  align-items-center text-white show_confirm"
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

                    <div id="useradd-4" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Opportunities') }}</h5>
                                    <small class="text-muted">{{ __('Assigned Opportunities for this account') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('opportunities.create', ['account', $salesaccount->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                            data-title="{{ __('Create New Opportunities') }}"
                                            class="btn btn-sm btn-primary btn-icon-only ">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive ">
                                <table class="table datatable" id="datatable1">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="status">
                                                {{ __('Opportunities Stage') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Amount') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Assigned User') }}</th>
                                            @if (Gate::check('opportunities show') || Gate::check('opportunities edit') || Gate::check('opportunities delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($opportunitiess as $opportunities)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('opportunities.edit', $opportunities->id) }}"
                                                        data-size="md" data-title="{{ __('Opportunities Details') }}"
                                                        class="action-item text-primary">
                                                        {{ $opportunities->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-success p-2 px-3 rounded">{{ !empty($opportunities->stages) ? $opportunities->stages->name : '-' }}</span>
                                                </td>

                                                <td>
                                                    <span
                                                        class="budget">{{ currency_format_with_sym($opportunities->amount) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ !empty($opportunities->assign_user) ? $opportunities->assign_user->name : '-' }}</span>
                                                </td>
                                                @if (Gate::check('opportunities show') || Gate::check('opportunities edit') || Gate::check('opportunities delete'))
                                                    <td class="text-end">

                                                        @can('opportunities show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a data-size="md"
                                                                    data-url="{{ route('opportunities.show', $opportunities->id) }}"
                                                                    data-bs-toggle="tooltip" data-ajax-popup="true"
                                                                    data-title="{{ __('Opportunities Details') }}"
                                                                    title="{{ __(' details') }}"class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('opportunities edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('opportunities.edit', $opportunities->id) }}"
                                                                    data-bs-toggle="tooltip"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-title="{{ __('Opportunities Edit') }}"title="{{ __(' Edit') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('opportunities delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['opportunities.destroy', $opportunities->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm  align-items-center text-white show_confirm"
                                                                    data-bs-toggle="tooltip" title='Delete'>
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            @endcan
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div id="useradd-5" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Cases') }}</h5>
                                    <small class="text-muted">{{ __('Assigned Cases for this account') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('commoncases.create', ['account', $salesaccount->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                            data-title="{{ __('Create New Common Case') }}"
                                            class="btn btn-sm btn-primary btn-icon-only ">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable2">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="budget">{{ __('Number') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Priority') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Created At') }}</th>
                                            @if (Gate::check('case show') || Gate::check('case edit') || Gate::check('case delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cases as $case)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('commoncases.edit', $case->id) }}" data-size="md"
                                                        data-title="{{ __('Cases Details') }}"
                                                        class="action-item text-primary">
                                                        {{ $case->name }}
                                                    </a>
                                                </td>
                                                <td class="budget">
                                                    {{ $case->number }}
                                                </td>
                                                <td>
                                                    @if ($case->status == 0)
                                                        <span
                                                            class="badge bg-primary p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                                    @elseif($case->status == 1)
                                                        <span
                                                            class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                                    @elseif($case->status == 2)
                                                        <span
                                                            class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                                    @elseif($case->status == 3)
                                                        <span
                                                            class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                                    @elseif($case->status == 4)
                                                        <span
                                                            class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                                    @elseif($case->status == 5)
                                                        <span
                                                            class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($case->priority == 0)
                                                        <span
                                                            class="badge bg-primary p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                                    @elseif($case->priority == 1)
                                                        <span
                                                            class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                                    @elseif($case->priority == 2)
                                                        <span
                                                            class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                                    @elseif($case->priority == 3)
                                                        <span
                                                            class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ company_date_formate($case->created_at) }}</span>
                                                </td>
                                                @if (Gate::check('case show') || Gate::check('case edit') || Gate::check('case delete'))
                                                    <td class="text-end">

                                                        @can('case show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a data-size="md"
                                                                    data-url="{{ route('commoncases.show', $case->id) }}"
                                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                    title="{{ __('Details') }}"
                                                                    data-title="{{ __('Cases Details') }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('case edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('commoncases.edit', $case->id) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-title="{{ __('Cases Edit') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('case delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['commoncases.destroy', $case->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm  align-items-center text-white show_confirm"
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

                    <div id="useradd-6" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Sales Documents') }}</h5>
                                    <small class="text-muted">{{ __('Assigned Documents for this account') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('salesdocument.create', ['account', $salesaccount->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                            data-title="{{ __('Create New Documents') }}"
                                            class="btn btn-sm btn-primary btn-icon-only">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="budget">{{ __('File') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Created At') }}</th>
                                            @if (Gate::check('salesdocument show') || Gate::check('salesdocument edit') || Gate::check('salesdocument delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documents as $document)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('salesdocument.edit', $document->id) }}"
                                                        data-size="md" data-title="{{ __('Document Details') }}"
                                                        class="action-item text-primary">
                                                        {{ $document->name }}</a>
                                                </td>
                                                <td class="budget">
                                                    @if (!empty($document->attachment))
                                                        <a href="{{ get_file($document->attachment) }}"
                                                            download=""><i class="ti ti-download"></i></a>
                                                    @else
                                                        <span>
                                                            {{ __('No File') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($document->status == 0)
                                                        <span
                                                            class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                                    @elseif($document->status == 1)
                                                        <span
                                                            class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                                    @elseif($document->status == 2)
                                                        <span
                                                            class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                                    @elseif($document->status == 3)
                                                        <span
                                                            class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ company_date_formate($document->created_at) }}</span>
                                                </td>
                                                @if (Gate::check('salesdocument show') || Gate::check('salesdocument edit') || Gate::check('salesdocument delete'))
                                                    <td class="text-end">

                                                        @can('salesdocument show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a data-size="lg"
                                                                    data-url="{{ route('salesdocument.show', $document->id) }}"
                                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                    title="{{ __('Details') }}"
                                                                    data-title="{{ __('Document Details') }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('salesdocument edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('salesdocument.edit', $document->id) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-title="{{ __('Document Edit') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('salesdocument delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['salesdocument.destroy', $document->id]]) !!}
                                                                <a href="#!"
                                                                    class="mx-3 btn btn-sm  align-items-center text-white show_confirm"
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

                    <div id="useradd-7" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Quotes') }}</h5>
                                    <small class="text-muted">{{ __('Assigned Quotes for this account') }}</small>
                                </div>
                                <div class="col">

                                    <div class="float-end">
                                        <a data-url="{{ route('quote.create', ['account', $salesaccount->id]) }}"
                                            data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip"
                                            data-title="{{ __('Create New Quote') }}"
                                            title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Created At') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Amount') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Assign User') }}</th>

                                            @if (Gate::check('quote show') || Gate::check('quote edit') || Gate::check('quote delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quotes as $quote)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('quote.edit', $quote->id) }}" data-size="md"
                                                        data-title="{{ __('Quote') }}"
                                                        class="action-item text-primary">
                                                        {{ $quote->name }}</a>
                                                </td>
                                                <td>
                                                    @if ($quote->status == 0)
                                                        <span class="badge bg-secondary p-2 px-3 rounded"
                                                            style="width: 79px;">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                                    @elseif($quote->status == 1)
                                                        <span class="badge bg-info p-2 px-3 rounded"
                                                            style="width: 79px;">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ company_date_formate($quote->created_at) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ currency_format_with_sym($quote->getTotal()) }}</span>
                                                </td>
                                                <td>
                                                    <span class="col-sm-12"><span
                                                            class="text-m">{{ ucfirst(!empty($quote->assign_user) ? $quote->assign_user->name : '-') }}</span></span>
                                                </td>
                                                @if (Gate::check('quote show') || Gate::check('quote edit') || Gate::check('quote delete'))
                                                    <td class="text-end">

                                                        @can('quote show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('quote.show', $quote->id) }}"
                                                                    data-size="md"class="mx-3 btn btn-sm align-items-center text-white "
                                                                    data-bs-toggle="tooltip" title="{{ __('Quick View') }}"
                                                                    data-title="{{ __('Quote Details') }}">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('quote edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('quote.edit', $quote->id) }}"
                                                                    class="mx-3 btn btn-sm align-items-center text-white"
                                                                    data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                                    data-title="{{ __('Edit Quote') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('quote delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['quote.destroy', $quote->id]]) !!}
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

                    <div id="useradd-8" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Sales Orders') }}</h5>
                                    <small class="text-muted">{{ __('Assigned SalesOrder for this account') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('salesorder.create', ['account', $salesaccount->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                            data-title="{{ __('Create New SalesOrder') }}"
                                            class="btn btn-sm btn-primary btn-icon-only">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Created At') }} </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Amount') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Assigned User') }}</th>
                                            @if (Gate::check('salesorder show') || Gate::check('salesorder edit') || Gate::check('salesorder delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesorders as $salesorder)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('salesorder.edit', $salesorder->id) }}"
                                                        data-size="md" data-title="{{ __('SalesOrder') }}"
                                                        class="action-item text-primary">
                                                        {{ $salesorder->name }}</a>
                                                </td>
                                                <td>
                                                    @if ($salesorder->status == 0)
                                                        <span class="badge bg-secondary p-2 px-3 rounded"
                                                            style="width: 79px;">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                                                    @elseif($salesorder->status == 1)
                                                        <span class="badge bg-info p-2 px-3 rounded"
                                                            style="width: 79px;">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ company_date_formate($salesorder->created_at) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ currency_format_with_sym($salesorder->getTotal()) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ ucfirst(!empty($salesorder->assign_user) ? $salesorder->assign_user->name : '-') }}</span>
                                                </td>
                                                @if (Gate::check('salesorder show') || Gate::check('salesorder edit') || Gate::check('salesorder delete'))
                                                    <td class="text-end">
                                                        @can('salesorder show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('salesorder.show', $salesorder->id) }}"
                                                                    data-size="md"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-bs-toggle="tooltip" title="{{ __('Quick View') }}"
                                                                    data-title="{{ __('SalesOrders Details') }}">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('salesorder edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('salesorder.edit', $salesorder->id) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                                    data-title="{{ __('Edit SalesOrders') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('salesorder delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['salesorder.destroy', $salesorder->id]]) !!}
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

                    <div id="useradd-9" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Sales Invoices') }}</h5>
                                    <small class="text-muted">{{ __('Assigned SalesInvoice for this account') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('salesinvoice.create', ['account', $salesaccount->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                            data-title="{{ __('Create New SalesInvoice') }}"
                                            class="btn btn-sm btn-primary btn-icon-only">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Created At') }} </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Amount') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Assigned User') }}</th>
                                            @if (Gate::check('salesinvoice show') || Gate::check('salesinvoice edit') || Gate::check('salesinvoice delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesinvoices as $salesinvoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('salesinvoice.edit', $salesinvoice->id) }}"
                                                        data-size="md" data-title="{{ __('SalesInvoice') }}"
                                                        class="action-item text-primary">
                                                        {{ $salesinvoice->name }}</a>
                                                </td>
                                                <td>
                                                    @if ($salesinvoice->status == 0)
                                                        <span class="badge bg-secondary p-2 px-3 rounded"
                                                            style="width: 91px;">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$salesinvoice->status]) }}</span>
                                                    @elseif($salesinvoice->status == 1)
                                                        <span class="badge bg-danger p-2 px-3 rounded"
                                                            style="width: 91px;">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$salesinvoice->status]) }}</span>
                                                    @elseif($salesinvoice->status == 2)
                                                        <span class="badge bg-warning p-2 px-3 rounded"
                                                            style="width: 91px;">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$salesinvoice->status]) }}</span>
                                                    @elseif($salesinvoice->status == 3)
                                                        <span class="badge bg-success p-2 px-3 rounded"
                                                            style="width: 91px;">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$salesinvoice->status]) }}</span>
                                                    @elseif($salesinvoice->status == 4)
                                                        <span class="badge bg-info p-2 px-3 rounded"
                                                            style="width: 91px;">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$salesinvoice->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ company_date_formate($salesinvoice->created_at) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ currency_format_with_sym($salesinvoice->getTotal()) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ ucfirst(!empty($salesinvoice->assign_user) ? $salesinvoice->assign_user->name : '-') }}</span>
                                                </td>
                                                @if (Gate::check('salesinvoice show') || Gate::check('salesinvoice edit') || Gate::check('salesinvoice delete'))
                                                    <td class="text-end">
                                                        @can('salesinvoice show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a href="{{ route('salesinvoice.show', $salesinvoice->id) }}"
                                                                    data-bs-toggle="tooltip" title="{{ __('Quick View') }}"
                                                                    class="mx-3 btn btn-sm align-items-center text-white "
                                                                    data-title="{{ __('Invoice Details') }}">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('salesinvoice edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('salesinvoice.edit', $salesinvoice->id) }}"
                                                                    data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                                    class="mx-3 btn btn-sm align-items-center text-white "
                                                                    data-title="{{ __('Edit Invoice') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('salesinvoice delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['salesinvoice.destroy', $salesinvoice->id]]) !!}
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

                        <div id="useradd-10" class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <h5>{{ __('Calls') }}</h5>
                                        <small class="text-muted">{{ __('Assigned Call for this account') }}</small>
                                    </div>
                                    <div class="col">
                                        <div class="float-end">
                                            <a data-size="lg" data-url="{{ route('call.create', ['call', 0]) }}"
                                                data-ajax-popup="true" data-bs-toggle="tooltip"
                                                data-title="{{ __('Create New Call') }}"
                                                title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
                                                <i class="ti ti-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table datatable" id="datatable3">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                                <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                                </th>
                                                <th scope="col" class="sort" data-sort="completion">
                                                    {{ __('Date Start') }}</th>
                                                <th scope="col" class="sort" data-sort="completion">
                                                    {{ __('Assigned User') }}</th>
                                                @if (Gate::check('call show') || Gate::check('call edit') || Gate::check('call delete'))
                                                    <th scope="col" class="text-end">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($calls as $call)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('call.edit', $call->id) }}" data-size="md"
                                                            data-title="{{ __('Call') }}"
                                                            class="action-item text-primary">
                                                            {{ $call->name }}</a>
                                                    </td>
                                                    <td>
                                                        @if ($call->status == 0)
                                                            <span class="badge bg-success p-2 px-3 rounded"
                                                                style="width: 73px;">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}</span>
                                                        @elseif($call->status == 1)
                                                            <span class="badge bg-warning p-2 px-3 rounded"
                                                                style="width: 73px;">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}</span>
                                                        @elseif($call->status == 2)
                                                            <span class="badge bg-danger p-2 px-3 rounded"
                                                                style="width: 73px;">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="budget">{{ company_date_formate($call->start_date) }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="budget">{{ ucfirst(!empty($call->assign_user) ? $call->assign_user->name : '') }}</span>
                                                    </td>
                                                    @if (Gate::check('call show') || Gate::check('call edit') || Gate::check('call delete'))
                                                        <td class="text-end">
                                                            @can('call show')
                                                                <div class="action-btn bg-warning ms-2">
                                                                    <a data-size="md"
                                                                        data-url="{{ route('call.show', $call->id) }}"
                                                                        data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                        data-title="{{ __('Call Details') }}"
                                                                        title="{{ __('Quick View') }}"class="mx-3 btn btn-sm d-inline-flex align-items-center text-white  ">
                                                                        <i class="ti ti-eye"></i>
                                                                    </a>
                                                                </div>
                                                            @endcan
                                                            @can('call edit')
                                                                <div class="action-btn bg-info ms-2">
                                                                    <a href="{{ route('call.edit', $call->id) }}"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center text-white "
                                                                        data-bs-toggle="tooltip"
                                                                        data-title="{{ __('Edit Call') }}"title="{{ __('Details') }}"><i
                                                                            class="ti ti-pencil"></i></a>
                                                                </div>
                                                            @endcan
                                                            @can('call delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['call.destroy', $call->id]]) !!}
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

                    <div id="useradd-11" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Meetings') }}</h5>
                                    <small class="text-muted">{{ __('Assigned Meeting for this account') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg" data-url="{{ route('meeting.create', ['meeting', 0]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                            data-title="{{ __('Create New Meeting') }}" title="{{ __('Create') }}"
                                            class="btn btn-sm btn-primary btn-icon">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable3">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Date Start') }}</th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Assigned User') }}</th>
                                            @if (Gate::check('meeting show') || Gate::check('meeting edit') || Gate::check('meeting delete'))
                                                <th scope="col" class="text-end">{{ __('Action') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($meetings as $meeting)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('meeting.edit', $meeting->id) }}" data-size="md"
                                                        data-title="{{ __('Meeting') }}"
                                                        class="action-item text-primary">
                                                        {{ $meeting->name }}</a>
                                                </td>
                                                <td>
                                                    @if ($meeting->status == 0)
                                                        <span class="badge bg-success p-2 px-3 rounded"
                                                            style="width: 73px;">{{ __(Modules\Sales\Entities\Meeting::$status[$meeting->status]) }}</span>
                                                    @elseif($meeting->status == 1)
                                                        <span class="badge bg-warning p-2 px-3 rounded"
                                                            style="width: 73px;">{{ __(Modules\Sales\Entities\Meeting::$status[$meeting->status]) }}</span>
                                                    @elseif($meeting->status == 2)
                                                        <span class="badge bg-danger p-2 px-3 rounded"
                                                            style="width: 73px;">{{ __(Modules\Sales\Entities\Meeting::$status[$meeting->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ company_date_formate($meeting->start_date) }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="budget">{{ ucfirst(!empty($meeting->assign_user) ? $meeting->assign_user->name : '') }}</span>
                                                </td>
                                                @if (Gate::check('meeting show') || Gate::check('meeting edit') || Gate::check('meeting delete'))
                                                    <td class="text-end">
                                                        @can('meeting show')
                                                            <div class="action-btn bg-warning ms-2">
                                                                <a data-size="md"
                                                                    data-url="{{ route('meeting.show', $meeting->id) }}"
                                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                                    data-title="{{ __('Meeting Details') }}"title="{{ __('Quick View') }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                                    <i class="ti ti-eye"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('meeting edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('meeting.edit', $meeting->id) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                    data-bs-toggle="tooltip"
                                                                    data-title="{{ __('Edit Meeting') }}"
                                                                    title="{{ __('Details') }}"><i
                                                                        class="ti ti-pencil"></i></a>
                                                            </div>
                                                        @endcan
                                                        @can('meeting delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['meeting.destroy', $meeting->id]]) !!}
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
    <script>
        $(document).on('click', '#billing_data', function() {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })
    </script>
    <script>
        $(document).on('change', 'select[name=opportunity]', function() {
            var opportunities = $(this).val();
            getaccount(opportunities);
        });

        function getaccount(opportunities_id) {
            $.ajax({
                url: '{{ route('quote.getaccount') }}',
                type: 'POST',
                data: {
                    "opportunities_id": opportunities_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#amount').val(data.opportunitie.amount);
                    $('#name').val(data.opportunitie.name);
                    $('#account_name').val(data.account.name);
                    $('#account_id').val(data.account.id);
                    $('#billing_address').val(data.account.billing_address);
                    $('#shipping_address').val(data.account.shipping_address);
                    $('#billing_city').val(data.account.billing_city);
                    $('#billing_state').val(data.account.billing_state);
                    $('#shipping_city').val(data.account.shipping_city);
                    $('#shipping_state').val(data.account.shipping_state);
                    $('#billing_country').val(data.account.billing_country);
                    $('#billing_postalcode').val(data.account.billing_postalcode);
                    $('#shipping_country').val(data.account.shipping_country);
                    $('#shipping_postalcode').val(data.account.shipping_postalcode);

                }
            });
        }
    </script>

    <script>
        $(document).on('change', 'select[name=parent]', function() {
            var parent = $(this).val();

            getparent(parent);
        });

        function getparent(bid) {
            $.ajax({
                url: '{{ route('call.getparent') }}',
                type: 'POST',
                data: {
                    "parent": bid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#parent_id').empty();
                    {{-- $('#parent_id').append('<option value="">{{__('Select Parent')}}</option>'); --}}

                    $.each(data, function(key, value) {
                        $('#parent_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    if (data == '') {
                        $('#parent_id').empty();
                    }
                }
            });
        }
    </script>
@endpush
