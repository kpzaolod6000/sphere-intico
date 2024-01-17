@extends('layouts.main')
@section('page-title')
    {{__('Manage Call')}}
@endsection
@section('title')
        {{__('Call')}}
@endsection
@section('page-breadcrumb')
    {{__('Call')}}
@endsection
@section('page-action')
<div>
    <a href="{{ route('call.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>

    @can('call create')
    <a href="#" data-size="lg" data-url="{{ route('call.create',['call',0]) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Create New Call')}}" title="{{__('Create')}}" class="btn btn-sm btn-primary btn-icon">
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
    @foreach($calls as $call)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex align-items-center">
                        @if($call->status == 0)
                        <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}</span>
                        @elseif($call->status == 1)
                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}</span>
                        @elseif($call->status == 2)
                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}</span>
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
                                @if(Gate::check('call show') || Gate::check('call edit') || Gate::check('call delete'))

                                @can('call edit')
                                    <a href="{{ route('call.edit', $call->id) }}" class="dropdown-item"
                                        data-bs-whatever="{{ __('Edit Call') }}" data-bs-toggle="tooltip"
                                        data-title="{{ __('Edit Call') }}"><i class="ti ti-pencil"></i>
                                        {{ __('Edit') }}</a>
                                @endcan
                                @can('call show')
                                    <a href="#" data-url="{{ route('call.show', $call->id) }}"
                                        data-ajax-popup="true" data-size="md"class="dropdown-item"
                                        data-bs-whatever="{{ __('Call Details') }}"
                                        data-bs-toggle="tooltip"
                                        data-title="{{ __('Call Details') }}"><i class="ti ti-eye"></i>
                                        {{ __('Details') }}</a>
                                @endcan

                                @can('call delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['call.destroy', $call->id]]) !!}
                                <a href="#!" class="dropdown-item show_confirm" data-bs-toggle="tooltip">
                                    <i class="ti ti-trash"></i>{{ __('Delete') }}
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
                                    <img alt="user-image" class="img-fluid rounded-circle" @if(!empty($call->avatar)) src="{{(!empty($call->avatar))? asset(get_file("upload/profile/".$call->avatar)): asset(url("./assets/img/clients/160x160/img-1.png"))}}" @else  avatar="{{$call->name}}" @endif>
                                </div>
                                <h5 class="h6 mt-2 mb-1 text-primary">{{ $call->name}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-3">

        <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Call') }}" data-url="{{ route('call.create',['call',0]) }}" style="padding: 90px 10px;">
             <div class="badge bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{__('New Call')}}</h6>
            <p class="text-muted text-center">{{__('Click here to add New Call')}}</p>
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
                url: '{{route('call.getparent')}}',
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
                    if (data == '') {
                        $('#parent_id').empty();
                    }
                }
            });
        }
    </script>
@endpush
