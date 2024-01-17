@extends('layouts.main')
@section('page-title')
    {{ __('Manage Sales Account') }}
@endsection
@section('title')
    {{ __('Account') }}
@endsection
@section('page-breadcrumb')
    {{__('Sales Account')}}
@endsection
@section('page-action')
<div>
    @can('salesaccount import')
        <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Sales Account Import')}}" data-url="{{ route('salesaccount.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
        </a>
    @endcan
    <a href="{{ route('salesaccount.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
        title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>
    @can('salesaccount create')
        <a data-url="{{ route('salesaccount.create',['account',0]) }}" data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip"data-title="{{__('Create New Sales Account')}}"title="{{__('Create')}}" class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
@section('filter')
@endsection
@section('content')
<div class="row">
    @foreach ($accounts as $account)
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
                                @if (Gate::check('salesaccount show') || Gate::check('salesaccount edit') || Gate::check('salesaccount delete'))
                                @can('salesaccount edit')
                                <a href="{{ route('salesaccount.edit', $account->id) }}" data-size="md" class="dropdown-item"
                                    data-bs-whatever="{{ __('Edit Account') }}" data-bs-toggle="tooltip"
                                    data-title="{{ __('Edit Account') }}"><i class="ti ti-pencil"></i>
                                    {{ __('Edit') }}</a>
                                @endcan
                                @can('salesaccount show')
                                    <a data-url="{{ route('salesaccount.show', $account->id) }}"
                                        data-ajax-popup="true" data-size="md" class="dropdown-item"
                                        data-bs-whatever="{{ __('Sales Account Details') }}"
                                        data-bs-toggle="tooltip"
                                        data-title="{{ __('Account Details') }}"><i class="ti ti-eye"></i>
                                        {{ __('Details') }}</a>
                                @endcan

                                @can('salesaccount delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['salesaccount.destroy', $account->id]]) !!}
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
                                    <img alt="user-image" class="img-fluid rounded-circle" @if (!empty($account->avatar)) src="{{ !empty($account->avatar) ? asset(Storage::url('upload/profile/' . $account->avatar)) : asset(url('assets/images/user/avatar-2.png')) }}" @else  avatar="{{ $account->name }}" @endif>

                                </div>
                                <h5 class="h6 mt-2 mb-1 text-primary">{{ ucfirst($account->name) }}</h5>
                                <div class="mb-1"><a href="#" class="text-sm small text-muted">{{ $account->email }}</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endforeach
    <div class="col-md-3">

        <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Account') }}" data-url="{{ route('salesaccount.create', ['account', 0]) }}" style="padding: 90px 10px;">
             <div class="badge bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{__('New Account')}}</h6>
            <p class="text-muted text-center">{{__('Click here to add New Account')}}</p>
        </a>
     </div>
</div>
@endsection
