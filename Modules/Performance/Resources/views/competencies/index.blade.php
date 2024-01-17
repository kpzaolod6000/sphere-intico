@extends('layouts.main')

@section('page-title')
  {{ __('Manage Competencies') }}
@endsection

@section('page-breadcrumb')
   {{ __('Competencies') }}
@endsection

@section('page-action')
<div>
    @can('competencies create')
        <a  data-url="{{ route('competencies.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Competencies') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
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
                                <th>{{ __('Type') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($competencies as $competency)
                                <tr>
                                    <td>{{ $competency->name }}</td>
                                    <td>{{ !empty($competency->getPerformance_type->name) ? $competency->getPerformance_type->name : '-' }}
                                    <td class="Action">
                                        <span>
                                            @can('competencies edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('competencies/' . $competency->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Competencies') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('competencies delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['competencies.destroy', $competency->id], 'id' => 'delete-form-' . $competency->id]) !!}
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
