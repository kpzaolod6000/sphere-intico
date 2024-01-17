@extends('layouts.main')
@section('page-title')
    {{ __('Manage Cases') }}
@endsection
@section('title')
    {{ __('Cases') }}
@endsection
@section('page-breadcrumb')
    {{ __('Cases') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ Module::asset('Sales:Resources/assets/css/custom.css') }}">
@endpush
@section('page-action')
    <div>
        @stack('addButtonHook')

        <a href="{{ route('commoncases.grid') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @can('case create')
            <a data-size="lg" data-url="{{ route('commoncases.create', ['commoncases', 0]) }}" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Case') }}"title="{{ __('Create') }}"
                class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{ __('File') }}</th>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Number') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Account') }}</th>
                                    <th scope="col" class="sort" data-sort="status">{{ __('Status') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Priority') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Assigned User') }}</th>
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
                                                data-title="{{ __('Cases Details') }}" class="text-primary">
                                                {{ $case->name }}
                                            </a>
                                        </td>
                                        <td class="budget">
                                            @if (!empty($case->attachments))
                                                <a href="{{ get_file($case->attachments) }}" download=""><i
                                                        class="ti ti-download"></i></a>
                                            @else
                                                <span>
                                                    {{ __('No File') }}
                                                </span>
                                            @endif

                                        </td>
                                        <td>{{ $case->number }}</td>
                                        <td>
                                            {{ !empty($case->accounts->name) ? $case->accounts->name : '--' }}
                                        </td>
                                        <td>
                                            @if ($case->status == 0)
                                                <span class="badge bg-success p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                            @elseif($case->status == 1)
                                                <span class="badge bg-info p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                            @elseif($case->status == 2)
                                                <span class="badge bg-warning p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                            @elseif($case->status == 3)
                                                <span class="badge bg-danger p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                            @elseif($case->status == 4)
                                                <span class="badge bg-danger p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                            @elseif($case->status == 5)
                                                <span class="badge bg-warning p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$status[$case->status]) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($case->priority == 0)
                                                <span class="badge bg-primary p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                            @elseif($case->priority == 1)
                                                <span class="badge bg-info p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                            @elseif($case->priority == 2)
                                                <span class="badge bg-warning p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                            @elseif($case->priority == 3)
                                                <span class="badge bg-danger  p-2 px-3 rounded"
                                                    style="width: 73px;">{{ __(Modules\Sales\Entities\CommonCase::$priority[$case->priority]) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ !empty($case->assign_user) ? $case->assign_user->name : '' }}
                                        </td>
                                        @if (Gate::check('case show') || Gate::check('case edit') || Gate::check('case delete'))
                                            <td class="text-end">

                                                @can('case show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a data-size="md" data-url="{{ route('commoncases.show', $case->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            data-title="{{ __('Cases Details') }}"title="{{ __('Quick View') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('case edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('commoncases.edit', $case->id) }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white "
                                                            data-bs-toggle="tooltip"
                                                            data-title="{{ __('Edit Cases') }}"title="{{ __('Details') }}"><i
                                                                class="ti ti-pencil"></i></a>
                                                    </div>
                                                @endcan
                                                @can('case delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['commoncases.destroy', $case->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm   align-items-center text-white show_confirm"
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
        </div>
    </div>
@endsection
