@extends('layouts.main')
@section('page-title')
    {{ __('Manage Sales Document') }}
@endsection
@section('title')
    {{ __('Document') }}
@endsection
@section('page-breadcrumb')
   {{ __('Sales Document') }}
@endsection
@section('page-action')
<div>
    @stack('addButtonHook')

    <a href="{{ route('salesdocument.grid') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
    title="{{ __('Grid View') }}">
    <i class="ti ti-layout-grid text-white"></i>
    </a>

    @can('salesdocument create')
    <a data-size="lg" data-url="{{ route('salesdocument.create', ['document', 0]) }}" data-ajax-popup="true"
    data-bs-toggle="tooltip" data-title="{{ __('Create New Sales Document') }}" title="{{ __('Create') }}"
    class="btn btn-sm btn-primary btn-icon">
    <i class="ti ti-plus"></i>
    </a>
    @endcan
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ Module::asset('Sales:Resources/assets/css/custom.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{ __('File') }}</th>
                                    <th scope="col" class="sort" data-sort="status">{{ __('Status') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">
                                        {{ __('Created At') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">
                                        {{ __('Assign User') }}</th>
                                        @if (Gate::check('salesdocument show') || Gate::check('salesdocument edit') || Gate::check('salesdocument delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                    <tr>
                                        <td>
                                            <a href="{{ route('salesdocument.edit', $document->id) }}" data-size="lg" data-title="{{ __('Sales Document Details') }}"
                                                class="action-item text-primary">
                                                {{ ucfirst($document->name) }}
                                            </a>
                                        </td>
                                        <td class="budget">
                                            @if (!empty($document->attachment))
                                                <a href="{{ get_file($document->attachment)}}"
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
                                                    class="badge bg-success p-2 px-3 rounded" style="width: 73px;">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                            @elseif($document->status == 1)
                                                <span
                                                    class="badge bg-warning p-2 px-3 rounded" style="width: 73px;">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                            @elseif($document->status == 2)
                                                <span
                                                    class="badge bg-danger p-2 px-3 rounded" style="width: 73px;">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                            @elseif($document->status == 3)
                                                <span
                                                    class="badge bg-danger p-2 px-3 rounded" style="width: 73px;">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="budget">{{company_date_formate($document->created_at) }}</span>
                                        </td>
                                        <td>
                                            <span class="col-sm-12"><span
                                                    class="text-m">{{ ucfirst(!empty($document->assign_user) ? $document->assign_user->name : '-') }}</span></span>
                                        </td>
                                        @if (Gate::check('salesdocument show') || Gate::check('salesdocument edit') || Gate::check('salesdocument delete'))
                                            <td class="text-end">
                                                @can('salesdocument show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a data-size="lg"
                                                            data-url="{{ route('salesdocument.show', $document->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            title="{{ __('Quick View') }}"
                                                            data-title="{{ __('Sales Document Details') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('salesdocument edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('salesdocument.edit', $document->id) }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white "
                                                            data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                            data-title="{{ __('Edit Document') }}"><i
                                                                class="ti ti-pencil"></i></a>
                                                    </div>
                                                @endcan
                                                @can('salesdocument delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['salesdocument.destroy', $document->id]]) !!}
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
@endsection
