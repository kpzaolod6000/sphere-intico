@extends('layouts.main')
@section('page-title')
    {{ __('Manage Cases') }}
@endsection
@section('title')
   {{ __('Common Cases') }}
@endsection
@section('page-breadcrumb')
    {{__('Case')}}
@endsection
@push('scripts')
<script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
@section('page-action')
<div>
    <a href="{{ route('commoncases.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
    title="{{ __('List View') }}">
    <i class="ti ti-list text-white"></i>
    </a>

    @can('case create')
    <a href="#" data-size="lg" data-url="{{ route('commoncases.create', ['commoncases', 0]) }}" data-ajax-popup="true"
    data-bs-toggle="tooltip" data-title="{{ __('Create New Case') }}"title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
    <i class="ti ti-plus"></i>
    </a>
    @endcan
</div>
@endsection
@section('filter')
@endsection
@section('content')
<div class="row">
    @foreach ($commonCases as $commonCase)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex align-items-center">
                    </div>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <button type="button" class="btn dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="feather icon-more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if (Gate::check('case show') || Gate::check('case edit') || Gate::check('case delete'))

                                @can('case edit')
                                    <a href="{{ route('commoncases.edit', $commonCase->id) }}"
                                        class="dropdown-item" data-bs-whatever="{{ __('Edit Common case') }}"
                                        data-bs-toggle="tooltip" data-title="{{ __('Edit Common case') }}"><i class="ti ti-pencil"></i>
                                        {{ __('Edit') }}</a>
                                @endcan
                                @can('case show')
                                    <a href="#" data-url="{{ route('commoncases.show', $commonCase->id) }}"
                                        data-ajax-popup="true" data-size="md"class="dropdown-item"
                                        data-bs-whatever="{{ __('Common case Details') }}"
                                        data-bs-toggle="tooltip" data-title="{{ __('Cases Details') }}"><i class="ti ti-eye"></i>
                                        {{ __('Details') }}</a>
                                @endcan

                                @can('case delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['commoncases.destroy', $commonCase->id]]) !!}
                                <a href="#!" class="dropdown-item  show_confirm" data-bs-toggle="tooltip">
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
                                    <img alt="user-image" class="img-fluid rounded-circle" width="120px" @if (!empty($commonCase->attachments)) src="{{ check_file(get_file($commonCase->attachments)) ? get_file($commonCase->attachments) : get_file('uploads/users-avatar/avatar.png')}}" @else  src="{{ get_file('uploads/users-avatar/avatar.png')}}" @endif>
                                </div>
                                <h5 class="h6 mt-4 mb-1 text-primary">{{ $commonCase->name }}</h5>
                                <div class="mb-1"><a href="#" class="text-sm small text-muted" data-toggle="tooltip" data-placement="right"
                                    title="Account Name">{{ !empty($commonCase->accounts) ? $commonCase->accounts->name : '-' }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-3">

        <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Common case') }}" data-url="{{ route('commoncases.create', ['commoncases', 0]) }}" style="padding: 90px 10px;">
             <div class="badge bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{__('New Common case')}}</h6>
            <p class="text-muted text-center">{{__('Click here to add New Common case')}}</p>
        </a>
     </div>
</div>




@endsection
