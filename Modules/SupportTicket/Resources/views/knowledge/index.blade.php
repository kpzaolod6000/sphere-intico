@extends('layouts.main')

@section('page-title')
    {{ __('Manage Knowledge') }}
@endsection

@section('page-breadcrumb')
    {{ __('Support Ticket') }},{{ __('Knowledge') }}
@endsection

@section('page-action')
    <div>
        @permission('knowledgebase import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Knowledge Import') }}"
                data-url="{{ route('knowledge.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        @permission('knowledgebase create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create Knowledge') }}"
                data-url="{{ route('support-ticket-knowledge.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
        @permission('knowledgebasecategory manage')
            <a href="{{ route('knowledge-category.index') }}" class="btn btn-sm btn-primary" data-toggle="tooltip"
                title="{{ __('Knowledge Category') }}">
                <i class="ti ti-vector-bezier"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-body">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table pc-dt-simple">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th class="w-25">{{ __('Title') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    @if (Laratrust::hasPermission('knowledgebase edit') || Laratrust::hasPermission('knowledgebase delete'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($knowledges as $index => $knowledge)
                                    <tr>
                                        <td scope="row">{{ ++$index }}</td>
                                        <td>
                                            {{ $knowledge->title }}
                                        </td>
                                        <td class="knw_desc">
                                            {{ strip_tags($knowledge->description) }}
                                        </td>
                                        <td>
                                            <span class="font-weight-normal">
                                                {{ !empty($knowledge->getCategoryInfo) ? $knowledge->getCategoryInfo->title : '-' }}
                                            </span>
                                        </td>
                                        @if (Laratrust::hasPermission('knowledgebase edit') || Laratrust::hasPermission('knowledgebase delete'))
                                            <td>
                                                @permission('knowledgebase edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a data-ajax-popup="true" data-size="lg"
                                                            data-title="{{ __('Edit Knowledge') }}"
                                                            data-url="{{ route('support-ticket-knowledge.edit', $knowledge->id) }}"
                                                            data-toggle="tooltip" title="{{ __('Edit') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip"><span class="text-white"> <i
                                                                    class="ti ti-pencil"></i></span></a>
                                                    </div>
                                                @endpermission
                                                @permission('knowledgebase delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        <form method="POST"
                                                            action="{{ route('support-ticket-knowledge.destroy', $knowledge->id) }}"
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
