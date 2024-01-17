@extends('layouts.main')
@section('page-title')
    {{ __('Manage Account Industry') }}
@endsection
@section('title')
    <div class="page-header-title">
        <h4 class="m-b-10">{{ __('Account Industry') }}</h4>
    </div>
@endsection
@section('page-breadcrumb')
    {{ __('Constant') }},
   {{ __('Account Industry') }}
@endsection
@section('page-action')
    @can('accountindustry create')
        <div class="action-btn ms-2">
            <a data-size="md" data-url="{{ route('account_industry.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Account Industry') }}" title="{{ __('Create') }}"
                class="btn btn-sm btn-primary btn-icon m-1">
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
                                    <th scope="col" class="sort" data-sort="name">{{ __('industry') }}</th>
                                    @if (Gate::check('accountindustry edit') || Gate::check('accountindustry delete'))
                                        <th class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($industrys as $industry)
                                    <tr>
                                        <td class="sorting_1">{{ $industry->name }}</td>
                                        @if (Gate::check('accountindustry edit') || Gate::check('accountindustry delete'))
                                            <td class="action text-end">
                                                @can('accountindustry edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a data-size="md"
                                                            data-url="{{ route('account_industry.edit', $industry->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            data-title="{{ __('Edit Account Industry') }}"
                                                            title="{{ __('Edit') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('accountindustry delete')
                                                    <div class="action-btn bg-danger ms-2 float-end">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['account_industry.destroy', $industry->id]]) !!}
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
