@extends('layouts.main')
@section('page-title')
    {{ __('Manage Documents Type') }}
@endsection
@section('title')
    {{ __('Document Type') }}
@endsection
@section('page-breadcrumb')
    {{ __('Constant') }},
    {{ __('Document Type') }}
@endsection
@section('page-action')
    @can('salesdocumenttype create')
        <div class="action-btn ms-2">
            <a data-size="md" data-url="{{ route('salesdocument_type.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                data-title="{{ __('Create New Documents Type') }}" class="btn btn-sm btn-primary btn-icon m-1">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
@endsection
@section('filter')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('sales::layouts.system_setup')
        </div>
        <div class="col-sm-9">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('type') }}</th>
                                    @if (Gate::check('salesdocumenttype ediy') || Gate::check('salesdocumenttype delete'))
                                        <th class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($types as $type)
                                    <tr>
                                        <td class="sorting_1">{{ $type->name }}</td>
                                        @if (Gate::check('salesdocumenttype show') || Gate::check('salesdocumenttype delete'))
                                            <td class="action text-end">
                                                @can('salesdocumenttype edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a data-size="md"
                                                            data-url="{{ route('salesdocument_type.edit', $type->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            data-title="{{ __('Edit Documents type') }}" title="{{ __('Edit') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('salesdocumenttype delete')
                                                    <div class="action-btn bg-danger ms-2 float-end">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['salesdocument_type.destroy', $type->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm align-items-center text-white show_confirm"
                                                            data-bs-toggle="tooltip" title='Delete'>
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </td>
                                        @endif
                                    </tr>
                                    @empty
                                    @include('layouts.nodatafound')
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
