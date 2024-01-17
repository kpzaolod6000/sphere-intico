@extends('layouts.main')
@section('page-title')
    {{ __('Manage Call') }}
@endsection
@section('title')
    {{ __('Call') }}
@endsection
@section('page-breadcrumb')
    {{ __('Call') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('call.grid') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>

        @can('call create')
            <a data-size="lg" data-url="{{ route('call.create',['call',0]) }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Create New Call') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@section('filter')
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
                                    <th scope="col" class="sort" data-sort="budget">{{ __('Parent') }}</th>
                                    <th scope="col" class="sort" data-sort="status">{{ __('Status') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Date Start') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Assigned User') }}</th>
                                    @if (Gate::check('call show') || Gate::check('call edit') || Gate::check('call delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($calls as $call)
                                    <tr>
                                        <td>
                                            <a href="{{ route('call.edit', $call->id) }}" data-size="md" data-title="{{ __('show Call') }}"
                                                class="action-item text-primary">
                                                {{ ucfirst($call->name) }}
                                            </a>
                                        </td>
                                        <td class="budget">
                                            {{ ucfirst($call->parent) }}
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
                                            <span class="budget">{{ company_date_formate($call->start_date) }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="budget">{{ ucfirst(!empty($call->assign_user) ? $call->assign_user->name : '') }}</span>
                                        </td>
                                        @if (Gate::check('call show') || Gate::check('call edit') || Gate::check('call delete'))
                                            <td class="text-end">
                                                @can('call show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a data-size="md" data-url="{{ route('call.show', $call->id) }}"
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
        </div>
    </div>
@endsection
@push('scripts')
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
