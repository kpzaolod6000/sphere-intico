@extends('layouts.main')
@section('page-title')
    {{ __('Manage Tickets') }}
@endsection
@section('title')
    {{ __('Tickets') }}
@endsection
@section('page-breadcrumb')
    {{ __('Tickets') }}
@endsection
@section('page-action')
<div class="col-auto pe-0">
        <select class="form-select" id="projects"
            onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);"
            style="width: 121px;">
            <option value="{{ route('support-tickets.grid') }}">{{ __('All Tickets') }}</option>
            <option value="{{ route('support-tickets.grid', 'in-progress') }}"
                @if ($status == 'in-progress') selected @endif>{{ __('In Progress') }}</option>
            <option value="{{ route('support-tickets.grid', 'on-hold') }}"
                @if ($status == 'on-hold') selected @endif>{{ __('On Hold') }}</option>
            <option value="{{ route('support-tickets.grid', 'closed') }}"
                @if ($status == 'closed') selected @endif>{{ __('Closed') }}</option>
        </select>
    </div>
    <div class="col-auto pe-0 pt-2">
        <a href="{{ route('support-tickets.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
    </div>
    <div class="col-auto ps-1 pt-2">
        @permission('ticket create')
            <a href="{{ route('support-tickets.create') }}" data-size="lg" data-bs-toggle="tooltip"
                data-title="{{ __('Create New Ticket') }}" title="{{ __('Create') }}"
                class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        @foreach ($tickets as $index => $ticket)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">

                            <td><span
                                    class="badge fix_badge @if ($ticket->status == 'In Progress') bg-warning @elseif($ticket->status == 'On Hold') bg-danger @else bg-success @endif  p-2 px-3 rounded">{{ __($ticket->status) }}</span>
                            </td>
                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @if (Laratrust::hasPermission('ticket show') || Laratrust::hasPermission('ticket edit') || Laratrust::hasPermission('ticket delete'))
                                        @permission('ticket show')
                                            <a href="{{ route('support-tickets.edit', $ticket->id) }}"
                                                data-size="md"class="dropdown-item" data-bs-toggle="tooltip"><i
                                                    class="ti ti-eye"></i>{{ __('Edit & Reply') }}
                                            </a>

                                            <a href="{{ route('ticket.view', [$ticket->workspace->slug, \Illuminate\Support\Facades\Crypt::encrypt($ticket->ticket_id)]) }}"
                                                data-ajax-popup="true" data-size="md"class="dropdown-item"
                                                data-bs-toggle="tooltip"><i class="ti ti-eye"></i>{{ __('Details') }}
                                            </a>
                                        @endpermission

                                        @permission('ticket delete')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['support-tickets.destroy', $ticket->id]]) !!}
                                            <a href="#!" class="dropdown-item show_confirm" data-bs-toggle="tooltip">
                                                <i class="ti ti-trash"></i>{{ __('Delete') }}
                                            </a>
                                            {!! Form::close() !!}
                                        @endpermission
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 justify-content-between">
                            <div class="col-12">
                                <div class="text-center client-box">
                                    <div class="avatar-parent-child">
                                        <img alt="user-image" class="img-fluid rounded-circle"
                                            @if (!empty($ticket->avatar)) src="{{ !empty($ticket->avatar) ? asset(Storage::url('upload/profile/' . $ticket->avatar)) : asset(url('./assets/img/clients/160x160/img-1.png')) }}" @else  avatar="{{ $ticket->name }}" @endif>
                                    </div>
                                    <h5 class="h6 mt-2 mb-1 text-primary">{{ $ticket->name }}</h5>
                                    <div class="mb-1"><a href="#"
                                            class="text-sm small text-muted">{{ $ticket->email }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-3">
            <a href="{{ route('support-tickets.create') }}" class="btn-addnew-project" data-size="lg"
                data-title="{{ __('Create New Tickets') }}" style="padding: 90px 10px;">
                <div class="badge bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">{{__('New Tickets')}}</h6>
                <p class="text-muted text-center">{{ __('Click here to add New Tickets') }}</p>
            </a>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('Modules/SupportTicket/Resources/assets/js/letter.avatar.js') }}"></script>
@endpush
