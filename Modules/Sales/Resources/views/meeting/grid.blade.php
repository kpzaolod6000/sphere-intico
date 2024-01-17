@extends('layouts.main')
@section('page-title')
    {{__('Manage Meeting')}}
@endsection
@section('title')
        {{__('Meeting')}}
@endsection
@section('page-breadcrumb')
    {{__('Meeting')}}
@endsection
@section('page-action')
<div>
    <a href="{{ route('meeting.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>

    @can('meeting create')
        <a href="#" data-size="lg" data-url="{{ route('meeting.create',['meeting',0]) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Create New Meeting')}}" title="{{__('Create')}}" class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
</div>
@endsection
@section('filter')
@endsection
@section('content')
<div class="row">
    @foreach($meetings as $meeting)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex align-items-center">
                        @if($meeting->status == 0)
                        <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Meeting::$status[$meeting->status]) }}</span>
                        @elseif($meeting->status == 1)
                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Meeting::$status[$meeting->status]) }}</span>
                        @elseif($meeting->status == 2)
                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Meeting::$status[$meeting->status]) }}</span>
                        @endif
                    </div>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <button type="button" class="btn dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="feather icon-more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if(Gate::check('meeting show') || Gate::check('meeting edit') || Gate::check('meeting delete'))

                                @can('meeting edit')
                                    <a href="{{ route('meeting.edit', $meeting->id) }}" class="dropdown-item"
                                        data-bs-whatever="{{ __('Edit Meeting') }}" data-bs-toggle="tooltip"
                                        data-title="{{ __('Edit Meeting') }}"><i class="ti ti-pencil"></i>
                                        {{ __('Edit') }}</a>
                                @endcan
                                @can('meeting show')
                                    <a href="#" data-url="{{ route('meeting.show', $meeting->id) }}"
                                        data-ajax-popup="true"data-size="md" class="dropdown-item"
                                        data-bs-whatever="{{ __('Meeting Details') }}"
                                        data-bs-toggle="tooltip" data-title="{{ __('Meeting Details') }}"><i class="ti ti-eye"></i>
                                        {{ __('Details') }}</a>
                                @endcan

                                @can('meeting delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['meeting.destroy', $meeting->id]]) !!}
                                <a href="#!" class="dropdown-item show_confirm" data-bs-toggle="tooltip" >
                                    <i class="ti ti-trash me-1"></i>{{ __('Delete') }}
                                </a>
                                {!! Form::close() !!}

                                @endcan
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2 justify-content-between">
                        <div class="col-12">
                            <div class="text-center client-box">
                                <div class="avatar-parent-child">
                                    <img alt="user-image" class="img-fluid rounded-circle" @if(!empty($meeting->avatar)) src="{{(!empty($meeting->avatar))? asset(Storage::url("upload/profile/".$meeting->avatar)): asset(url("./assets/img/clients/160x160/img-1.png"))}}" @else  avatar="{{$meeting->name}}" @endif>
                                </div>
                                <h5 class="h6 mt-4 mb-1 text-primary">
                                    {{ ucfirst($meeting->name)}}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-3">

        <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Meeting') }}" data-url="{{ route('meeting.create',['meeting',0]) }}" style="padding: 90px 10px;">
             <div class="badge bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{__('New Meeting')}}</h6>
            <p class="text-muted text-center">{{__('Click here to add New Meeting')}}</p>
        </a>
     </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/letter.avatar.js') }}"></script>
    <script>

        $(document).on('change', 'select[name=parent]', function () {

            var parent = $(this).val();

            getparent(parent);
        });

        function getparent(bid) {

            $.ajax({
                url: '{{route('meeting.getparent')}}',
                type: 'POST',
                data: {
                    "parent": bid, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#parent_id').empty();
                    {{--$('#parent_id').append('<option value="">{{__('Select Parent')}}</option>');--}}

                    $.each(data, function (key, value) {
                        $('#parent_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
