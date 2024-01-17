
@extends('layouts.main')
@section('page-title')
    {{ __('Manage Appraisal') }}
@endsection
@push('css')
    <style>
        @import url({{ asset('Modules/Performance/Resources/assets/css/font-awesome.css') }});
    </style>
        <link rel="stylesheet" href="{{ asset('Modules/Performance/Resources/assets/css/custom.css') }}">
@endpush
@push('scripts')
<script src="{{ asset('Modules/Performance/Resources/assets/js/bootstrap-toggle.js') }}"></script>

@endpush

@section('page-breadcrumb')
    {{ __('Appraisal') }}
@endsection

@section('page-action')
<div>
    @can('appraisal create')
        <a  data-url="{{ route('appraisal.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Appraisal') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
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
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Target Rating') }}</th>
                                <th>{{ __('Overall Rating') }}</th>
                                <th>{{ __('Appraisal Date') }}</th>
                                @if (Gate::check('appraisal edit') || Gate::check('appraisal delete') || Gate::check('appraisal show'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appraisals as $appraisal)
                                @php
                                $designation=!empty($appraisal->employees) ?  $appraisal->employees->designation->id : '-';
                                $targetRating =  Modules\Performance\Entities\Appraisal::getTargetrating($designation,$competencyCount);
                                if(!empty($appraisal->rating)&&($competencyCount!=0))
                                {
                                    $rating = json_decode($appraisal->rating,true);
                                    $starsum = array_sum($rating);
                                    $overallrating = $starsum/$competencyCount;
                                }
                                else{
                                    $overallrating = 0;
                                }
                                @endphp
                                @php
                                    if (!empty($appraisal->rating)) {
                                        $rating = json_decode($appraisal->rating, true);
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
                                    <td>{{ !empty($appraisal->branches) ? $appraisal->branches->name : '' }}</td>
                                    <td>{{ !empty($appraisal->employees) ?  $appraisal->employees->department->name : '-'}}
                                    </td>
                                    <td>{{ !empty($appraisal->employees) ?  $appraisal->employees->designation->name : '-' }}
                                    </td>
                                    <td>{{ !empty($appraisal->employees) ? $appraisal->employees->name : '-' }}</td>
                                    <td >
                                        @for($i=1; $i<=5; $i++)
                                         @if($targetRating < $i)
                                            @if(is_float($targetRating) && (round($targetRating) == $i))
                                            <i class="text-warning fas fa-star-half-alt"></i>
                                            @else
                                            <i class="fas fa-star"></i>
                                            @endif
                                         @else
                                         <i class="text-warning fas fa-star"></i>
                                         @endif
                                        @endfor

                                       <span class="theme-text-color">({{number_format($targetRating,1)}})</span>
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
                                    <td>{{ $appraisal->appraisal_date }}</td>
                                    <td class="Action">
                                        @if (Gate::check('appraisal edit') || Gate::check('appraisal delete') || Gate::check('appraisal show'))
                                            <span>
                                                @can('appraisal show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a  class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ route('appraisal.show', $appraisal->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Appraisal Detail') }}"
                                                            data-bs-original-title="{{ __('View') }}">
                                                            <i class="ti ti-eye text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('appraisal edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ route('appraisal.edit', $appraisal->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Appraisal') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('appraisal delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['appraisal.destroy', $appraisal->id], 'id' => 'delete-form-' . $appraisal->id]) !!}
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
