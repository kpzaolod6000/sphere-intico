@extends('layouts.main')
@section('page-title')
    {{ __('Project-Template') }}
@endsection
@section('page-breadcrumb')
    {{ __('Manage Project Template') }}
@endsection

@section('page-action')
<a href="{{ route('project-template.list') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"title="{{ __('List View') }}">
    <i class="ti ti-list text-white"></i>
</a>
@endsection
@section('content')
<section class="section mt-5">
    <div class="filters-content">
        <div class="row  d-flex grid">
            @isset($projects)
                @foreach ($projects as $project)
                    <div class="col-md-6 col-xl-3 All  {{ $project->status }}">
                        <div class="card">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    @if ($project->is_active)
                                        <a href="@can('project manage') {{ route('projects.show', [$project->id]) }} @endcan"
                                            class="">
                                            <img alt="{{ $project->name }}" class="img-fluid wid-30 me-2 fix_img"
                                                avatar="{{ $project->name }}">
                                        </a>
                                    @else
                                        <a href="#" class="">
                                            <img alt="{{ $project->name }}" class="img-fluid wid-30 me-2 fix_img"
                                                avatar="{{ $project->name }}">
                                        </a>
                                    @endif

                                    <h5 class="mb-0">
                                        @if ($project->is_active)
                                            <a href="@can('project manage') {{ route('projects.show', [$project->id]) }} @endcan"
                                                title="{{ $project->name }}" class="">{{ $project->name }}<i class="px-2 ti ti-eye"></i></a>
                                        @else
                                            <a href="#" title="{{ __('Locked') }}"
                                                class="">{{ $project->name }}</a>
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-header-right">
                                    <div class="btn-group card-option">

                                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="feather icon-more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end pointer">

                                            @if ($project->is_active)
                                                @can('project create')
                                                    <a class="dropdown-item" data-ajax-popup="true"
                                                        data-size="md" data-title="{{ __('Convert to project') }}"
                                                        data-url="{{ route('project-template.create',['project_id'=>$project->id,'type'=>'project']) }}">
                                                        <i class="ti ti-replace"></i> <span>{{ __('Convert to project') }}</span>
                                                    </a>
                                                @endcan
                                                @can('project delete')
                                                    <form id="delete-form-{{ $project->id }}"
                                                        action="{{ route('project-template.destroy',$project->id) }}" method="POST">
                                                        @csrf
                                                        <a href="#"
                                                            class="dropdown-item text-danger delete-popup bs-pass-para show_confirm"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $project->id }}">
                                                            <i class="ti ti-trash"></i> <span>{{ __('Delete') }}</span>
                                                        </a>
                                                        @method('DELETE')
                                                    </form>
                                                @endcan
                                            @else
                                                <a href="#" class="dropdown-item" title="{{ __('Locked') }}">
                                                    <i data-feather="lock"></i> <span>{{ __('Locked') }}</span>
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 justify-content-between">
                                    @if ($project->status == 'Finished')
                                        <div class="col-auto"><span
                                                class="badge rounded-pill bg-success">{{ __('Finished') }}</span>
                                        </div>
                                    @elseif($project->status == 'Ongoing')
                                        <div class="col-auto"><span
                                                class="badge rounded-pill bg-secondary">{{ __('Ongoing') }}</span>
                                        </div>
                                    @else
                                        <div class="col-auto"><span
                                                class="badge rounded-pill bg-warning">{{ __('OnHold') }}</span>
                                        </div>
                                    @endif

                                    <div class="col-auto">
                                    </div>
                                </div>
                                <p class="text-muted text-sm mt-3">{{ $project->description }}</p>
                                <div class="card mb-0 mt-3">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="mb-0">{{ $project->countTask() }}</h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Tasks') }}</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-0">{{ $project->countTaskComments() }}</h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Comments') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset
        </div>
    </div>

</section>
@endsection



@push('scripts')
    <script src="{{ Module::asset('Taskly:Resources/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/letter.avatar.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('.status-filter button').click(function() {
                $('.status-filter button').removeClass('active');
                $(this).addClass('active');

                var data = $(this).attr('data-filter');
                $grid.isotope({
                    filter: data
                })
            });

            var $grid = $(".grid").isotope({
                itemSelector: ".All",
                percentPosition: true,
                masonry: {
                    columnWidth: ".All"
                }
            })
        });
    </script>
@endpush
