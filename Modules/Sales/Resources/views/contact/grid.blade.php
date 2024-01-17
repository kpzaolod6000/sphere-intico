@extends('layouts.main')
@section('page-title')
    {{ __('Manage Contact') }}
@endsection
@section('title')
    <div class="page-header-title">
        <h4 class="m-b-10">{{ __('Contact') }}</h4>
    </div>
@endsection
@section('page-breadcrumb')
 {{__('Contact')}}
@endsection
@section('page-action')
<div>
    @can('contact import')
        <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Contact Import')}}" data-url="{{ route('contact.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
        </a>
    @endcan
    <a href="{{ route('contact.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
        title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>

    @can('contact create')
            <a data-url="{{ route('contact.create', ['contact', 0]) }}" data-size="lg" data-ajax-popup="true"
                data-bs-toggle="tooltip" data-title="{{ __('Create New Contact') }}"title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
    @endcan
</div>
@endsection
@section('filter')
@endsection
@push('scripts')
<script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        @foreach ($contacts as $contact)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="feather icon-more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                        @if (Gate::check('contact show') || Gate::check('contact edit') || Gate::check('contact delete'))

                                        @can('contact edit')
                                            <a href="{{ route('contact.edit', $contact->id) }}"data-size="md"  class="dropdown-item"
                                                data-bs-whatever="{{ __('Edit Contact') }}" data-bs-toggle="tooltip"
                                                data-title="{{ __('Edit Contact') }}"><i class="ti ti-pencil"></i>
                                                {{ __('Edit') }}</a>
                                        @endcan
                                        @can('contact show')
                                            <a data-url="{{ route('contact.show', $contact->id) }}"
                                                data-ajax-popup="true" class="dropdown-item"
                                                data-size="md" data-bs-whatever="{{ __('Contact Details') }}"
                                                data-bs-toggle="tooltip"
                                                data-title="{{ __('Contact Details') }}"><i class="ti ti-eye"></i>
                                                {{ __('Details') }}</a>
                                        @endcan

                                        @can('contact delete')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['contact.destroy', $contact->id]]) !!}
                                            <a href="#!" class="dropdown-item  show_confirm" data-bs-toggle="tooltip" >
                                                <i class="ti ti-trash"></i>{{ __('Delete') }}
                                            </a>
                                            {!! Form::close() !!}

                                        @endcan
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
                                        <img alt="user-image" class="img-fluid rounded-circle" @if (!empty($contact->avatar)) src="{{ !empty($contact->avatar) ? get_file($contact->avatar) : asset(url('./assets/img/clients/160x160/img-1.png')) }}" @else  avatar="{{ $contact->name }}" @endif>
                                    </div>
                                    <h5 class="h6 mt-2 mb-1 text-primary">{{ ucfirst($contact->name) }}</h5>
                                    <div class="mb-1"><a href="#" class="text-sm small text-muted">{{ $contact->email }}</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-3">
            <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Contact') }}" data-url="{{ route('contact.create', ['contact', 0]) }}" style="padding: 90px 10px;">
                <div class="badge bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">{{__('New Contact')}}</h6>
                <p class="text-muted text-center">{{__('Click here to add New Contact')}}</p>
            </a>
        </div>
    </div>
@endsection
