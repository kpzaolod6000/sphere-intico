@extends('layouts.main')
@section('page-title')
    {{ __('Manage Article') }}
@endsection
@section('title')
    {{ __('Article') }}
@endsection
@section('page-breadcrumb')
    {{ __('Article') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('article.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @can('article create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Article') }}"
                data-url="{{ route('article.create') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        @foreach ($articles as $article)
            <div class="col-md-6 col-xl-3 All">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary p-1 px-3 rounded">
                                <a href="#" class="text-white">{{ $article->type }}</a>
                            </span>
                        </div><br>
                        {{-- <h5 class="mb-0">
                            <a href="@can('article manage') {{ route('mindmap.index', [$article->id]) }} @endcan"
                                title="{{ $article->title }}" class="">{{ $article->title }}<i
                                    class="px-2 ti ti-eye"></i>
                            </a>
                        </h5> --}}
                        <h5 class="mb-0">
                            <a href="#"
                                title="{{ $article->title }}" class="">{{ $article->title }}
                            </a>
                        </h5>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('article duplicate')
                                        <a class="dropdown-item" data-ajax-popup="true" data-size="md"
                                            data-title="{{ __('Duplicate') }}"
                                            data-url="{{ route('article.copy', [$article->id]) }}">
                                            <i class="ti ti-copy"></i> <span>{{ __('Duplicate') }}</span>
                                        </a>
                                    @endcan
                                    @can('article edit')
                                        <a class="dropdown-item" data-ajax-popup="true" data-size="md"
                                            data-title="{{ __('Edit Article') }}"
                                            data-url="{{ route('article.edit', [$article->id]) }}">
                                            <i class="ti ti-pencil"></i> <span>{{ __('Edit') }}</span>
                                        </a>
                                    @endcan
                                    @can('article delete')
                                        <form id="delete-form-{{ $article->id }}"
                                            action="{{ route('article.destroy', [$article->id]) }}" method="POST">
                                            @csrf
                                            <a href="#"
                                                class="dropdown-item text-danger delete-popup bs-pass-para show_confirm"
                                                data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $article->id }}">
                                                <i class="ti ti-trash"></i> <span>{{ __('Delete') }}</span>
                                            </a>
                                            @method('DELETE')
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-auto text-end">
                            <p class="mb-0"><b>{{ __('Due Date:') }}</b> {{ $article->created_at->format('Y-m-d') }}</p>
                        </div>
                        <p class="text-muted text-sm mt-3">{{ substr($article->description, 0, 150) }}
                            @if (strlen($article->description) > 150)
                                ...
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
        @auth('web')
            @can('article create')
                <div class="col-md-3">
                    <a href="#" class="btn-addnew-project" data-ajax-popup="true" data-size="md"
                        data-title="{{ __('Create New Article') }}" data-url="{{ route('article.create') }}"
                        style="padding: 90px 10px;">
                        <div class="badge bg-primary proj-add-icon">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New Article') }}</h6>
                        <p class="text-muted text-center">{{ __('Click here to add New Article') }}</p>
                    </a>
                </div>
            @endcan
        @endauth
    </div>
@endsection
