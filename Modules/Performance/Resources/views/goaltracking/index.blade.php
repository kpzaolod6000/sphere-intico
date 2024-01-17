@extends('layouts.main')
@section('page-title')
    {{ __('Manage Goal Tracking') }}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('goaltracking.grid') }}" class="btn btn-sm btn-primary btn-icon"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @can('goaltracking create')
            <a  data-url="{{ route('goaltracking.create') }}" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create New Goal Tracking') }}" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Performance/Resources/assets/css/custom.css') }}">
@endpush
@section('page-breadcrumb')
    {{ __('Goal Tracking') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Goal Type') }}</th>
                                    <th>{{ __('Subject') }}</th>
                                    <th>{{ !empty(company_setting('hrm_branch_name')) ? company_setting('hrm_branch_name') : __('Branch') }}
                                    </th>
                                    <th>{{ __('Target Achievement') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Rating') }}</th>
                                    <th width="20%">{{ __('Progress') }}</th>
                                    @if (Gate::check('goaltracking edit') || Gate::check('goaltracking delete'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($goalTrackings as $goalTracking)
                                    <tr>
                                        <td>{{ !empty($goalTracking->goalType) ? $goalTracking->goalType->name : '' }}
                                        </td>
                                        <td>{{ $goalTracking->subject }}</td>
                                        <td>{{ !empty($goalTracking->branches) ? $goalTracking->branches->name : '' }}
                                        </td>
                                        <td>{{ $goalTracking->target_achievement }}</td>
                                        <td>{{ company_date_formate($goalTracking->start_date) }}</td>
                                        <td>{{ company_date_formate($goalTracking->end_date) }}</td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($goalTracking->rating < $i)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="text-warning fas fa-star"></i>
                                                @endif
                                            @endfor
                                        </td>
                                        <td>
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
                                        </td>
                                        <td class="Action">
                                            @if (Gate::check('goaltracking edit') || Gate::check('goaltracking delete'))
                                                <span>

                                                    @can('goaltracking edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a  class="mx-3 btn btn-sm  align-items-center"
                                                                data-size="lg"
                                                                data-url="{{ route('goaltracking.edit', $goalTracking->id) }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Goal Tracking') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('goaltracking delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['goaltracking.destroy', $goalTracking->id],
                                                                'id' => 'delete-form-' . $goalTracking->id,
                                                            ]) !!}
                                                            <a 
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </span>
                                            @endif
                                        </td>
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
