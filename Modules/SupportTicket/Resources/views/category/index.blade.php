@extends('layouts.main')
@section('page-title')
    {{ __('Manage Category') }}
@endsection
@section('page-breadcrumb')
    {{ __('Category') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-3">
            @include('supportticket::layouts.system_setup')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-11">
                            <h5 class="">
                                {{ __('Support Category') }}
                            </h5>
                        </div>
                        <div class="col-1  text-end">
                            @permission('ticketcategory create')
                                <a data-url="{{ route('ticket-category.create') }}" data-ajax-popup="true"
                                    data-bs-toggle="tooltip" title="{{ __('Create') }}" title="{{ __('Create') }}"
                                    data-title="{{ __('Create New Category') }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-plus"></i>
                                </a>
                            @endpermission
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover">

                        <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Color') }}</th>
                                @if (Laratrust::hasPermission('ticketcategory edit') || Laratrust::hasPermission('ticketcategory delete'))
                                    <th class="text-end">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $index => $category)
                                <tr>
                                    <th scope="row">{{ ++$index }}</th>
                                    <td>{{ $category->name }}</td>
                                    <td><span class="badge"
                                            style="background: {{ $category->color }}">&nbsp;&nbsp;&nbsp;</span></td>
                                    @if (Laratrust::hasPermission('ticketcategory edit') || Laratrust::hasPermission('ticketcategory delete'))
                                        <td>
                                            <span>
                                                @permission('ticketcategory delete')
                                                    <div class="action-btn bg-danger ms-2 float-end">
                                                        <form method="POST"
                                                            action="{{ route('ticket-category.destroy', $category->id) }}"
                                                            id="user-form-{{ $category->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button type="button"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                                data-bs-toggle="tooltip" title='Delete'>
                                                                <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endpermission
                                                @permission('ticketcategory edit')
                                                    <div class="action-btn bg-info ms-2 float-end">
                                                        <a class="mx-3 btn btn-sm align-items-center"
                                                            data-url="{{ route('ticket-category.edit', $category->id) }}"
                                                            data-ajax-popup="true"
                                                            data-title="{{ __('Edit Product Category') }}"
                                                            data-bs-toggle="tooltip" title="{{ __('Create') }}"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                            </span>
                                        </td>
                                    @endif
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
@endsection
