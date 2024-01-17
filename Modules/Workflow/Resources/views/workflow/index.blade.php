@extends('layouts.main')
@section('page-title')
    {{ __('Workflow') }}
@endsection
@section('page-breadcrumb')
    {{ __('Workflow') }}
@endsection
@section('page-action')
    <div>
        @can('workflow create')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create New Workflow') }}" data-url="{{ route('workflow.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
 @section('content')
     <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="products">
                            <thead>
                            <tr>
                                <th >{{__('Name')}}</th>
                                <th >{{__('Event')}}</th>
                                <th>{{__('Actions')}}</th>
                                @if (Gate::check('workflow delete') || Gate::check('workflow edit'))
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($workflows as $workflow)                                 
                                    <tr class="font-style"> 
                                        <td>{{ $workflow->name }}</td>
                                        <td>{{ !empty( $workflow->module) ?  $workflow->module->submodule: '-' }}</td>
                                        <td>{{ $workflow->workmodule($workflow->do_this) }}</td>
                                        
                                        @if (Gate::check('workflow delete') || Gate::check('workflow edit'))
                                            <td class="Action">
                                                @can('workflow edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm align-items-center" href="{{ route('workflow.edit',$workflow->id) }}" data-bs-toggle="tooltip" title="{{__('Edit')}}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('workflow delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['workflow.destroy', $workflow->id],'id'=>'delete-form-'.$workflow->id]) !!}
                                                        <a  class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="ti ti-trash text-white text-white"></i></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </td>
                                        @endif
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
 