@extends('layouts.main')
@section('page-title')
    {{ __('Manage Article') }}
@endsection
@section('page-breadcrumb')
    {{ __('Article') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('article.grid') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @can('article create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Article') }}"
                data-url="{{ route('article.create') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th> {{ __('Book') }}</th>
                                    <th> {{ __('Title') }}</th>
                                    <th> {{ __('Description') }}</th>
                                    <th> {{ __('Type') }}</th>
                                    <th width="10%"> {{ __('Action') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($articles as $article)
                                    <tr class="font-style">
                                        <td>{{ $article->book_name->title }}</td>
                                        <td>{{ $article->title }}</td>
                                        <td>{{ substr($article->description, 0, 50) }}
                                            @if (strlen($article->description) > 50)
                                                ...
                                            @endif
                                        </td>
                                        <td>{{ $article->type }}</td>
                                        <td class="Action">
                                            <span>
                                                @can('article duplicate')
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a data-size="md"
                                                            data-url="{{ route('article.copy', [$article->id]) }}" data-ajax-popup="true"
                                                            data-title="{{ __('Duplicate') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Duplicate') }}"><i
                                                                class="ti ti-copy text-white"></i></a>
                                                    </div>
                                                @endcan
                                                @can('article edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a class="mx-3 btn btn-sm align-items-center"
                                                            data-url="{{ route('article.edit', $article->id) }}"
                                                            data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}" data-title="{{ __('Edit Article') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('article delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['article.destroy', $article->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $article->id }}">
                                                            <i class="ti ti-trash text-white text-white"></i>
                                                        </a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endcan
                                            </span>
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