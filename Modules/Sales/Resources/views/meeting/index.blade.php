@extends('layouts.main')
@section('page-title')
    {{ __('Manage Meeting') }}
@endsection
@section('title')
    {{ __('Meeting') }}
@endsection
@section('page-breadcrumb')
    {{ __('Meeting') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')

        <a href="{{ route('meeting.grid') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>

        @can('meeting create')
            <a data-size="lg" data-url="{{ route('meeting.create',['meeting',0]) }}" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Create New Meeting') }}" title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
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
                                    <th scope="col" class="sort" data-sort="budget">{{ __('Parent') }}</th>
                                    <th scope="col" class="sort" data-sort="status">{{ __('Status') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Date Start') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Assigned User') }}</th>
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
                                                data-title="{{ __('Meeting Details') }}" class="action-item text-primary">
                                                {{ ucfirst($meeting->name) }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="budget">{{ ucfirst($meeting->parent) }}</span>
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
                                            <span class="budget">{{ company_date_formate($meeting->start_date) }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="budget">{{ ucfirst(!empty($meeting->assign_user) ? $meeting->assign_user->name : '') }}</span>
                                        </td>
                                        @if (Gate::check('meeting show') || Gate::check('meeting edit') || Gate::check('meeting delete'))
                                            <td class="text-end">
                                                @can('meeting show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a data-size="md" data-url="{{ route('meeting.show', $meeting->id) }}"
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
                                                            data-bs-toggle="tooltip" data-title="{{ __('Edit Meeting') }}"
                                                            title="{{ __('Details') }}"><i class="ti ti-pencil"></i></a>
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
@endsection
@push('scripts')
    <script>
        $(document).on('change', 'select[name=parent]', function() {

            var parent = $(this).val();

            getparent(parent);
        });

        function getparent(bid) {

            $.ajax({
                url: '{{ route('meeting.getparent') }}',
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
                }
            });
        }
    </script>
@endpush
