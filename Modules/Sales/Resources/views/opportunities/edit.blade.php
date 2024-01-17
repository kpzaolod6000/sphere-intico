@extends('layouts.main')
@section('page-title')
    {{ __('Opportunities Edit') }}
@endsection
@section('title')
    {{ __('Edit Opportunities') }} {{ '(' . $opportunities->name . ')' }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ Module::asset('Sales:Resources/assets/css/custom.css') }}">
@endpush
@section('page-action')
    <div class="btn-group" role="group">
        @if (!empty($previous))
            <div class="action-btn  ms-2">
                <a href="{{ route('opportunities.edit', $previous) }}" class="btn btn-sm btn-primary btn-icon m-1"
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
                <a href="{{ route('opportunities.edit', $next) }}" class="btn btn-sm btn-primary btn-icon m-1"
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
    {{ __('Opportunities') }},
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
                                class="list-group-item list-group-item-action border-0">{{ __('Sales Documents') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-4"
                                class="list-group-item list-group-item-action border-0">{{ __('Quotes') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-5"
                                class="list-group-item list-group-item-action border-0">{{ __('Sales Orders') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-6"
                                class="list-group-item list-group-item-action border-0">{{ __('Sales Invoices') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="card">
                        {{ Form::model($opportunities, ['route' => ['opportunities.update', $opportunities->id], 'method' => 'PUT']) }}
                        <div class="card-header">
                            <div class="float-end">
                                @if (module_is_active('AIAssistant'))
                                    @include('aiassistant::ai.generate_ai_btn', [
                                        'template_module' => 'opportunities',
                                        'module' => 'Sales',
                                    ])
                                @endif
                            </div>
                            <h5>{{ __('Overview') }}</h5>
                            <small class="text-muted">{{ __('Edit about your opportunities information') }}</small>
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
                                            {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                                            {!! Form::select('account', $account_name, null, ['class' => 'form-control']) !!}
                                        </div>
                                        @error('account')
                                            <span class="invalid-account" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('contact', __('Contact'), ['class' => 'form-label']) }}
                                            {!! Form::select('contact', $contact, null, ['class' => 'form-control']) !!}
                                            @error('contacts')
                                                <span class="invalid-contacts" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('stage', __('Stage'), ['class' => 'form-label']) }}
                                            {!! Form::select('stage', $stages, null, [
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'placeholder' => 'select Opportunities Stage',
                                            ]) !!}
                                        </div>
                                        @error('stage')
                                            <span class="invalid-stage" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                                            {{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone'), 'required' => 'required']) }}
                                            @error('amount')
                                                <span class="invalid-amount" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('probability', __('Probability'), ['class' => 'form-label']) }}
                                            {{ Form::number('probability', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone'), 'required' => 'required']) }}
                                            @error('probability')
                                                <span class="invalid-probability" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('close_date', __('Close Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('close_date', null, ['class' => 'form-control ', 'placeholder' => __('Enter Phone'), 'required' => 'required']) }}
                                            @error('close_date')
                                                <span class="invalid-close_date" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (module_is_active('Lead'))
                                        <div class="col-6">
                                            <div class="form-group">
                                                {{ Form::label('lead_source', __('Lead Source'), ['class' => 'form-label']) }}
                                                {!! Form::select('lead_source', $lead_source, null, ['class' => 'form-control ', 'required' => 'required']) !!}
                                                @error('lead_source')
                                                    <span class="invalid-lead_source" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{ Form::label('user', __(' Assigned User'), ['class' => 'form-label']) }}
                                            {!! Form::select('user', $user, $opportunities->user_id, ['class' => 'form-control']) !!}
                                        </div>
                                        @error('user')
                                            <span class="invalid-user" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                                            {!! Form::textarea('description', null, ['class' => 'form-control ', 'rows' => 3]) !!}
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
                        {{ Form::open(['route' => ['streamstore', ['opportunities', $opportunities->name, $opportunities->id]], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
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
                                    <input type="hidden" name="log_type" value="opportunities comment">

                                    <div class="col-12 field" data-name="attachments">
                                        <div class="attachment-upload">
                                            <div class="attachment-button">
                                                <div class="pull-left">
                                                    <div class="form-group">
                                                        {{ Form::label('attachment', __('Attachment'), ['class' => 'form-label']) }}
                                                        <input type="file"name="attachment" class="form-control mb-2"
                                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                        <img id="blah" width="20%" height="20%" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="attachments"></div>
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
                                @if ($remark->data_id == $opportunities->id)
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
                                                    <div class="col-11 d-block d-sm-flex align-items-center right-side">
                                                        <div
                                                            class="col-10 d-flex align-items-start flex-column justify-content-center mb-sm-0">
                                                            <div class="h6 ">{{ $remark->user_name }}
                                                            </div>
                                                            <span class="text-sm mb-0">
                                                                posted to <a href="#">{{ $remark->title }}</a> ,
                                                                {{ $stream->log_type }} <a
                                                                    href="#">{{ $remark->stream_comment }}</a>
                                                            </span>
                                                        </div>
                                                        <div class="col-2 d-flex align-items-center ">
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
                                    <h5>{{ __('Sales Documents') }}</h5>
                                    <small class="text-muted">{{ __('Assigned document for this opportunities') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('salesdocument.create', ['opportunities', $opportunities->id]) }}"
                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                            data-title="{{ __('Create New Documents') }}"title="{{ __('Create') }}"
                                            class="btn btn-sm btn-primary btn-icon-only   ">
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
                                            <th scope="col" class="sort" data-sort="budget">{{ __('File') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="status">{{ __('Status') }}
                                            </th>
                                            <th scope="col" class="sort" data-sort="completion">
                                                {{ __('Created At') }}</th>
                                            <th scope="col" class="text-end">{{ __('Action') }}</th>

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
                                                        <a href="{{ get_file($document->attachment) }}" download=""><i
                                                                class="ti ti-download"></i></a>
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

                    <div id="useradd-4" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Quotes') }}</h5>
                                    <small class="text-muted">{{ __('Assigned Quotes for this opportunities') }}</small>
                                </div>
                                <div class="col">

                                    <div class="float-end">
                                        <a data-url="{{ route('quote.create', ['opportunity', $opportunities->id]) }}"
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

                    <div id="useradd-5" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Sales Orders') }}</h5>
                                    <small
                                        class="text-muted">{{ __('Assigned SalesOrder for this opportunities') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('salesorder.create', ['opportunity', $opportunities->id]) }}"
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

                    <div id="useradd-6" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Sales Invoices') }}</h5>
                                    <small
                                        class="text-muted">{{ __('Assigned SalesInvoice for this opportunities') }}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg"
                                            data-url="{{ route('salesinvoice.create', ['opportunity', $opportunities->id]) }}"
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

@endpush
