@extends('layouts.main')
@section('page-title')
    {{ __('Manage Goal Tracking') }}
@endsection

@section('page-breadcrumb')
    {{ __('Goal Tracking') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('goaltracking.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @can('goaltracking create')
        <a  data-url="{{ route('goaltracking.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Goal Tracking') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Performance/Resources/assets/css/custom.css') }}">
@endpush
@section('filter')
@endsection
@section('content')
    <div class="row">
        @foreach ($goalTrackings as $goalTracking)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">

                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @if (Gate::check('job show') || Gate::check('job edit') || Gate::check('job delete'))
                                        @can('job edit')
                                            <a  data-url="{{ route('goaltracking.edit', $goalTracking->id) }}" data-ajax-popup="true" data-size="lg" class="dropdown-item"
                                                data-bs-whatever="{{ __('Edit Job') }}" data-bs-toggle="tooltip"
                                                data-title="{{ __('Edit Job') }}"><i class="ti ti-pencil"></i>
                                                {{ __('Edit') }}</a>
                                        @endcan


                                        @can('job delete')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['goaltracking.destroy', $goalTracking->id]]) !!}
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
                                    <div class="avatar-parent-child mb-3">
                                        <img alt="user-image" class="img-fluid rounded-circle"
                                            avatar="{{ !empty($goalTracking->goalType) ? $goalTracking->goalType->name : '' }}">
                                    </div>
                                    <div class="h6 mt-2 mb-1 ">
                                        <div class=" text-primary">{{ !empty($goalTracking->goalType) ? $goalTracking->goalType->name : '' }}</div>
                                    </div>
                                    <div class="mb-1"><a 
                                            class="text-sm small text-muted">{{ !empty($goalTracking->branches) ? $goalTracking->branches->name : __('All') }}</a>
                                    </div>
                                    <div class="progress-wrapper">
                                        <span class="progress-percentage"><small
                                                class="font-weight-bold"></small>{{ $goalTracking->progress }}%</span>
                                        <div class="progress progress-xs mt-2 w-100">
                                            <div class="progress-bar bg-{{ \Modules\Performance\Entities\GoalTracking::getProgressColor($goalTracking->progress) }}"
                                                role="progressbar" aria-valuenow="{{ $goalTracking->progress }}"
                                                aria-valuemin="0" aria-valuemax="100"
                                                style="width: {{ $goalTracking->progress }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-3">
            <a  data-url="{{ route('goaltracking.create') }}" class="btn-addnew-project" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Goal Tracking') }}" style="padding: 62px 10px;">
                <div class="badge bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">New Goal Tracking</h6>
                <p class="text-muted text-center">Click here to add New Goal Tracking</p>
            </a>
        </div>
    </div>
@endsection
