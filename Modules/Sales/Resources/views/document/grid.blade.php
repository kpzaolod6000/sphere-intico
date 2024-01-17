@extends('layouts.main')
@section('page-title')
    {{__('Manage Sales Document')}}
@endsection
@section('title')
        {{__('Document')}}
@endsection
@section('page-breadcrumb')
    {{__('Sales Document')}}
@endsection
@section('page-action')
<div>
    <a href="{{ route('salesdocument.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>

    @can('salesdocument create')
    <a data-size="lg" data-url="{{ route('salesdocument.create',['document',0]) }}" data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Create New Sales Document')}}"title="{{__('Create')}}" class="btn btn-sm btn-primary btn-icon">
        <i class="ti ti-plus"></i>
    </a>
    @endcan
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{ Module::asset('Sales:Resources/assets/css/custom.css') }}">
@endpush
@push('scripts')
<script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
@section('content')
<div class="row">
    @foreach($documents as $document)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex align-items-center">
                        @if($document->status == 0)
                        <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                        @elseif($document->status == 1)
                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                        @elseif($document->status == 2)
                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                        @elseif($document->status == 3)
                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesDocument::$status[$document->status]) }}</span>
                        @endif
                    </div>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <button type="button" class="btn dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="feather icon-more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if(Gate::check('salesdocument show') || Gate::check('salesdocument edit') || Gate::check('salesdocument delete'))

                                @can('salesdocument edit')
                                    <a href="{{ route('salesdocument.edit', $document->id) }}" class="dropdown-item"
                                        data-bs-whatever="{{ __('Edit Document') }}" data-bs-toggle="tooltip"
                                        data-title="{{ __('Edit Document') }}"><i class="ti ti-pencil"></i>
                                        {{ __('Edit') }}</a>
                                @endcan
                                @can('salesdocument show')
                                    <a data-url="{{ route('salesdocument.show', $document->id) }}"
                                        data-ajax-popup="true" data-size="lg"class="dropdown-item"
                                        data-bs-whatever="{{ __('Document Details') }}"
                                        data-bs-toggle="tooltip"
                                        data-title="{{ __('Sales Document Details') }}"><i class="ti ti-eye"></i>
                                        {{ __('Details') }}</a>
                                @endcan

                                @can('salesdocument delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['salesdocument.destroy', $document->id]]) !!}
                                <a href="#!" class="dropdown-item show_confirm" data-bs-toggle="tooltip" >
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
                                    <img alt="user-image" class="img-fluid rounded-circle" @if(!empty($document->avatar)) src="{{(!empty($document->avatar))? asset(Storage::url("upload/profile/".$document->avatar)): asset(url("./assets/img/clients/160x160/img-1.png"))}}" @else  avatar="{{$document->name}}" @endif>
                                </div>
                                <h5 class="h6  mb-1 mt-2 text-primary">{{ ucfirst($document->name)}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-3">

        <a href="#" class="btn-addnew-project"  data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Document') }}" data-url="{{ route('salesdocument.create',['document',0]) }}" style="padding: 90px 10px;">
             <div class="badge bg-primary proj-add-icon">
                <i class="ti ti-plus"></i>
            </div>
            <h6 class="mt-4 mb-2">{{__('New Document')}}</h6>
            <p class="text-muted text-center">{{__('Click here to add New Document')}}</p>
        </a>
     </div>
</div>
@endsection

