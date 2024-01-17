@extends('layouts.main')
@section('page-title')
    {{ __('Manage Shipping Provider') }}
@endsection
@section('title')
    {{ __('Shipping Provider') }}
@endsection
@section('page-breadcrumb')
    {{ __('Constant') }},
    {{ __('Shipping Provider') }}
@endsection
@section('page-action')
    @can('shippingprovider create')
        <div class="action-btn ms-2">
            <a data-size="md" data-url="{{ route('shipping_provider.create') }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Shipping Provider') }}" title="{{ __('Create') }}"
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
                                    <th scope="col" class="sort" data-sort="name">
                                        {{ __('Shipping Provider') }}</th>
                                    @if (Gate::check('shippingprovider edit') || Gate::check('shippingprovider delete'))
                                        <th class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shipping_providers as $shipping_provider)
                                    <tr>
                                        <td class="sorting_1">{{ $shipping_provider->name }}</td>
                                        @if (Gate::check('shippingprovider edit') || Gate::check('shippingprovider delete'))
                                            <td class="action text-end">
                                                @can('shippingprovider delete')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a data-size="md"
                                                            data-url="{{ route('shipping_provider.edit', $shipping_provider->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            data-title="{{ __('Edit Shipping Provider') }}"
                                                            title="{{ __('Edit') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('shippingprovider delete')
                                                    <div class="action-btn bg-danger ms-2 ">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['shipping_provider.destroy', $shipping_provider->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm align-items-center text-white show_confirm"
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
