@extends('layouts.main')
@section('page-title')
    {{ __('Manage Indicator') }}
@endsection

@section('page-breadcrumb')
   {{ __('Indicator') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Performance/Resources/assets/css/custom.css') }}">
@endpush
@section('page-action')
<div>
    @can('indicator create')
        <a  data-url="{{ route('indicator.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Indicator') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
</div>
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
                                <th>{{ !empty(company_setting('hrm_branch_name')) ? company_setting('hrm_branch_name') : __('Branch') }}</th>
                                <th>{{ !empty(company_setting('hrm_department_name')) ? company_setting('hrm_department_name') : __('Department') }}</th>
                                <th>{{ !empty(company_setting('hrm_designation_name')) ? company_setting('hrm_designation_name') : __('Designation') }}</th>
                                <th>{{ __('Overall Rating') }}</th>
                                <th>{{ __('Added By') }}</th>
                                <th>{{ __('Created At') }}</th>
                                @if (Gate::check('indicator edit') || Gate::check('indicator delete') || Gate::check('indicator show'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($indicators as $indicator)
                                @php
                                    if (!empty($indicator->rating)) {
                                        $rating = json_decode($indicator->rating, true);
                                        if (!empty($rating)) {
                                            $starsum = array_sum($rating);
                                            $overallrating = $starsum / count($rating);
                                        } else {
                                            $overallrating = 0;
                                        }
                                    } else {
                                        $overallrating = 0;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ !empty($indicator->branches) ? $indicator->branches->name : '' }}</td>
                                    <td>{{ !empty($indicator->departments) ? $indicator->departments->name : '' }}
                                    </td>
                                    <td>{{ !empty($indicator->designations) ? $indicator->designations->name : '' }}
                                    </td>
                                    <td>

                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($overallrating < $i)
                                                @if (is_float($overallrating) && round($overallrating) == $i)
                                                    <i class="text-warning fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="fas fa-star"></i>
                                                @endif
                                            @else
                                                <i class="text-warning fas fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="theme-text-color">({{ number_format($overallrating, 1) }})</span>
                                    </td>
                                    <td>{{ !empty($indicator->user) ? $indicator->user->name : '' }}</td>
                                    <td>{{ company_date_formate($indicator->created_at) }}</td>
                                    <td class="Action">
                                        @if (Gate::check('indicator edit') || Gate::check('indicator delete') || Gate::check('indicator show'))
                                            <span>
                                                @can('indicator show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a  class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ route('indicator.show', $indicator->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Indicator Detail ') }}"
                                                            data-bs-original-title="{{ __('View') }}">
                                                            <i class="ti ti-eye text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('indicator edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ route('indicator.edit', $indicator->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Indicator') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('indicator delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['indicator.destroy', $indicator->id], 'id' => 'delete-form-' . $indicator->id]) !!}
                                                        <a  class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete"><i
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

