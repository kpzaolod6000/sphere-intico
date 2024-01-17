@extends('layouts.main')
@section('page-title')
    {{ __('Manage Opportunities Stage') }}
@endsection
@section('title')
    {{ __('Opportunities Stage') }}
@endsection
@section('page-breadcrumb')
{{ __('Constant') }},
{{ __('Opportunities Stage') }}
@endsection
@section('page-action')
    @can('opportunitiesstage create')
        <div class="action-btn ms-2">
            <a data-size="md" data-url="{{ route('opportunities_stage.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Opportunities Stage') }}" title="{{ __('Create') }}"
                class="btn btn-sm btn-primary btn-icon">
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
                                    <th scope="col" class="sort" data-sort="name">
                                        {{ __('Opportunities Stage') }}</th>
                                    @if (Gate::check('opportunitiesstage edit') || Gate::check('opportunitiesstage delete'))
                                        <th class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($opportunities_stages as $opportunities_stage)
                                    <tr>
                                        <td class="sorting_1">{{ $opportunities_stage->name }}</td>
                                        @if (Gate::check('opportunitiesstage edit') || Gate::check('opportunitiesstage delete'))
                                            <td class="action text-end">
                                                @can('opportunitiesstage edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a data-size="md"
                                                            data-url="{{ route('opportunities_stage.edit', $opportunities_stage->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}"
                                                            data-title="{{ __('Edit Opportunities Stage') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('opportunitiesstage delete')
                                                    <div class="action-btn bg-danger ms-2 ">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['opportunities_stage.destroy', $opportunities_stage->id]]) !!}
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
