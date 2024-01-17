@extends('layouts.main')
@section('page-title')
    {{__('Manage Timesheet')}}
@endsection
@section('title')
    {{__('Contact')}}
@endsection
@section('page-breadcrumb')
   {{__('Timesheet')}}
@endsection
@section('page-action')
<div>
    @can('timesheet create')
        <a data-url="{{ route('timesheet.create') }}" data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip"data-title="{{__('Create New Timesheet')}}"title="{{__('Create')}}" class="btn btn-sm btn-primary">
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
                <div class="table-responsive overflow_hidden">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead class="thead-light">
                            <tr>
                                @if(\Auth::user()->type == 'company')
                                    <th>{{__('User')}}</th>
                                @endif
                                @if(module_is_active('Taskly'))
                                    <th>{{__('Project')}}</th>
                                    <th>{{__('Task')}}</th>
                                @endif
                                <th>{{__('Type')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Hours')}}</th>
                                <th>{{__('minutes')}}</th>
                                @if(Gate::check('timesheet edit') || Gate::check('timesheet delete'))
                                    <th scope="col" class="text-end">{{__('Action')}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($timesheets as $timesheet)
                                <tr>
                                    @if(\Auth::user()->type == 'company')
                                        <td>{{ $timesheet->user->name }}</td>
                                    @endif
                                    @if(module_is_active('Taskly'))
                                        <td>{{ !empty($timesheet->project->name) ? $timesheet->project->name:'-' }}</td>
                                        <td>{{ !empty($timesheet->task->title) ? $timesheet->task->title:'-' }}</td>
                                    @endif
                                    <td>{{ $timesheet->type }}</td>
                                    <td>{{ company_date_formate($timesheet->date) }}</td>
                                    <td>{{ $timesheet->hours }}</td>
                                    <td>{{ $timesheet->minutes }}</td>
                                    @if(Gate::check('timesheet edit') || Gate::check('timesheet delete'))
                                        <td scope="col" class="">
                                            @can('timesheet edit')
                                            <div class="action-btn bg-info">
                                                <a data-url="{{ route('timesheet.edit',$timesheet->id) }}" data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Edit Timesheet')}}" title="{{__('Edit')}}" class="btn btn-sm text-white">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            </div>
                                            @endcan
                                            @can('timesheet delete')
                                            <div class="action-btn bg-danger">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['timesheet.destroy', $timesheet->id]]) !!}
                                                <a href="#!" class="btn btn-sm align-items-center text-white show_confirm" data-bs-toggle="tooltip" title='Delete'>
                                                    <i class="ti ti-trash"></i>
                                                </a>
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
