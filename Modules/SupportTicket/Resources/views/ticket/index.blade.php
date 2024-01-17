@extends('layouts.main')

@section('page-title')
    {{ __('Manage Tickets') }}
@endsection
@section('page-breadcrumb')
{{ __('Tickets') }}
@endsection

@section('page-action')

    <div class="col-auto pe-0">
        <select class="form-select" id="projects" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" style="width: 121px;">
            <option value="{{route('support-tickets.search')}}">{{__('All Tickets')}}</option>
            <option value="{{route('support-tickets.search', 'in-progress')}}" @if($status == 'in-progress') selected @endif>{{__('In Progress')}}</option>
            <option value="{{route('support-tickets.search', 'on-hold')}}" @if($status == 'on-hold') selected @endif>{{__('On Hold')}}</option>
            <option value="{{route('support-tickets.search', 'closed')}}" @if($status == 'closed') selected @endif>{{__('Closed')}}</option>
        </select>
    </div>

    <div class="col-auto pe-0 pt-2">
        @stack('addButtonHook')
        <a href="{{ route('support-tickets.grid') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
    </div>
    <div class="col-auto ps-1 pt-2">
        @permission('ticket create')
                <a href="{{route('support-tickets.create')}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create')}}"><i class="ti ti-plus text-white"></i></a>
        @endpermission
    </div>

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            @if(session()->has('ticket_id') || session()->has('smtp_error'))
                <div class="alert alert-info bg-pr">
                    @if(session()->has('ticket_id'))
                        {!! Session::get('ticket_id') !!}
                        {{ Session::forget('ticket_id') }}
                    @endif
                    @if(session()->has('smtp_error'))
                        {!! Session::get('smtp_error') !!}
                        {{ Session::forget('smtp_error') }}
                    @endif
                </div>
            @endif
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="pc-dt-simple">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Ticket ID') }}</th>
                                <th>{{ __('Account Type') }}</th>
                                <th class="w-10">{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Created') }}</th>
                                <th class="text-end me-3">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $index => $ticket)
                                <tr>
                                    <th scope="row">{{++$index}}</th>
                                    <td class="Id sorting_1">
                                        <a class="btn btn-outline-primary" @permission('ticket show')href="{{route('support-tickets.edit',$ticket->id)}}" @else href="#" @endpermission>
                                            {{$ticket->ticket_id}}
                                        </a>
                                    </td>
                                    <td>{{ $ticket->account_type == '' ? 'custom' : $ticket->account_type }}</td>
                                    <td><span class="white-space">{{$ticket->name}}</span></td>
                                    <td>{{$ticket->email}}</td>
                                    <td><span class="white-space">{{$ticket->subject}}</span></td>
                                    <td><span class="badge badge-white p-2 px-3 rounded fix_badge" style="background: {{$ticket->color}};">{{$ticket->category_name}}</span></td>
                                    <td><span class="badge fix_badge @if($ticket->status == 'In Progress')bg-warning @elseif($ticket->status == 'On Hold') bg-danger @else bg-success @endif  p-2 px-3 rounded">{{__($ticket->status)}}</span></td>
                                    <td>{{$ticket->created_at->diffForHumans()}}</td>
                                    <td class="text-end">
                                        @permission('ticket show')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="{{ route('support-tickets.edit', $ticket->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Edit & Reply') }}"> <span class="text-white"> <i class="ti ti-corner-up-left"></i></span></a>
                                            </div>
                                            <div class="action-btn bg-warning ms-2">
                                                <a href="{{ route('ticket.view', [$ticket->workspace->slug,\Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)]) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Details') }}"> <span class="text-white"> <i class="ti ti-eye"></i></span></a>
                                            </div>
                                        @endpermission
                                        @permission('ticket delete')
                                            <div class="action-btn bg-danger ms-2">
                                                <form method="POST" action="{{route('support-tickets.destroy',$ticket->id)}}" id="user-form-{{$ticket->id}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="button" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-bs-toggle="tooltip"
                                                    title='Delete'>
                                                        <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endpermission
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
