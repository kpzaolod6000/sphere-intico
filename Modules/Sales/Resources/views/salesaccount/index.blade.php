@extends('layouts.main')
@section('page-title')
    {{ __('Manage Sales Account') }}
@endsection
@section('title')
    {{ __('Account') }}
@endsection
@section('page-breadcrumb')
    {{ __('Sales Account') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')

        @can('salesaccount import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Sales Account Import') }}"
                data-url="{{ route('salesaccount.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endcan
        <a href="{{ route('salesaccount.grid') }}" class="btn btn-sm btn-primary btn-icon"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>

        @can('salesaccount create')
            <a data-url="{{ route('salesaccount.create', ['account', 0]) }}" data-size="lg" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Sales Account') }}"title="{{ __('Create') }}"
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
                                    <th scope="col" class="sort" data-sort="Email">{{ __('Email') }}</th>
                                    <th scope="col" class="sort" data-sort="Phone">{{ __('Phone') }}</th>
                                    <th scope="col" class="sort" data-sort="Website">{{ __('Website') }}</th>
                                    <th scope="col" class="sort" data-sort="Assigned User">{{ __('Assigned User') }}
                                    </th>
                                    @if (Gate::check('salesaccount show') || Gate::check('salesaccount edit') || Gate::check('salesaccount delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td>
                                            <a href="{{ route('salesaccount.edit', $account->id) }}" data-size="md"
                                                data-title="{{ __('Account Details') }}" class="action-item text-primary">
                                                {{ ucfirst($account->name) }}
                                            </a>
                                        </td>
                                        <td class="budget">
                                            {{ $account->email }}
                                        </td>
                                        <td>
                                            <span class="budget"> {{ $account->phone }} </span>
                                        </td>
                                        <td>
                                            <span class="budget">{{ $account->website }}
                                                <a href="{{ $account->website }}" target="_blank"
                                                    class="btn btn-lg btn-sm d-inline-flex align-items-center">
                                                    <i class="ti ti-external-link text-success"
                                                        style="font-size: 1.5rem;"></i>
                                                </a>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="col-sm-12"><span
                                                    class="text-m">{{ ucfirst(!empty($account->assign_user) ? $account->assign_user->name : '-') }}</span></span>
                                        </td>

                                        @if (Gate::check('salesaccount show') || Gate::check('salesaccount edit') || Gate::check('salesaccount delete'))
                                            <td class="text-end">
                                                @can('salesaccount show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a data-size="md"
                                                            data-url="{{ route('salesaccount.show', $account->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            title="{{ __('Quick View') }}"
                                                            data-title="{{ __('Sales Account Details') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('salesaccount edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('salesaccount.edit', $account->id) }}"
                                                            data-size="md"class="mx-3 btn btn-sm d-inline-flex align-items-center text-white "
                                                            data-bs-toggle="tooltip"data-title="{{ __('Account Edit') }}"
                                                            title="{{ __('Details') }}"><i class="ti ti-pencil"></i></a>

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
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#billing_data', function() {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })
    </script>
@endpush
