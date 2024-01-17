@extends('layouts.main')

@section('page-title')
   {{ __('Manage Performance Type') }}
@endsection

@section('page-breadcrumb')
    {{ __('Performance Type') }}
@endsection

@section('page-action')
<div>
    @can('performancetype create')
        <a  data-url="{{ route('performanceType.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Performance Type') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
</div>
@endsection


@section('content')
<div class="row">
    <div class="col-sm-3">
        @include('hrm::layouts.hrm_setup')
    </div>
    <div class="col-sm-9">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 " >
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($performance_types as $performance_type)
                                <tr>
                                    <td>{{ $performance_type->name }}</td>
                                    <td class="Action">
                                        <span>
                                            @can('performancetype edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('performanceType/' . $performance_type->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Performance Type') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('performancetype delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['performanceType.destroy', $performance_type->id], 'id' => 'delete-form-' . $performance_type->id]) !!}
                                                    <a  class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan
                                        </span>
                                    </td>
                                </tr>
                               @empty
                                @include('layouts.nodatafound')
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
