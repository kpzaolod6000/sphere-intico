@extends('layouts.main')

@section('page-title')
    {{ __('Manage KnowledgeBase Category') }}
@endsection

@section('page-breadcrumb')
    {{ __('Knowledge Category') }}
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
                                {{ __('KnowledgeBase Category') }}
                            </h5>
                        </div>
                        <div class="col-1  text-end">
                            @permission('knowledgebasecategory create')
                                <div>
                                    <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                                        data-title="{{ __('Create Knowledgebase Category') }}"
                                        data-url="{{ route('knowledge-category.create') }}" data-toggle="tooltip"
                                        title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endpermission
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th class="w-50">{{ __('Title') }}</th>
                                @if (Laratrust::hasPermission('knowledgebasecategory edit') || Laratrust::hasPermission('knowledgebasecategory delete'))
                                    <th class="text-end">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($knowledges_category as $index => $knowledge)
                                <tr>
                                    <th scope="row">{{ ++$index }}</th>
                                    <td><span class="font-weight-bold white-space">{{ $knowledge->title }}</span></td>
                                    @if (Laratrust::hasPermission('knowledgebasecategory edit') || Laratrust::hasPermission('knowledgebasecategory delete'))
                                        <td>
                                            @permission('knowledgebasecategory delete')
                                                <div class="action-btn bg-danger ms-2 float-end">
                                                    <form method="POST"
                                                        action="{{ route('knowledge-category.destroy', $knowledge->id) }}"
                                                        id="user-form-{{ $knowledge->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <button type="button"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm"
                                                            data-toggle="tooltip" title="{{ __('Delete') }}">
                                                            <span class="text-white"> <i class="ti ti-trash"></i></span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endpermission
                                            @permission('knowledgebasecategory edit')
                                                <div class="action-btn bg-info ms-2 float-end">
                                                    <a data-ajax-popup="true" data-size="md"
                                                        data-title="{{ __('Create Knowledgebase Category') }}"
                                                        data-url="{{ route('knowledge-category.edit', $knowledge->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-toggle="tooltip" title="{{ __('Edit') }}"> <span
                                                            class="text-white"> <i class="ti ti-pencil"></i></span></a>
                                                </div>
                                            @endpermission
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
