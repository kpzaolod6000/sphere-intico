@extends('layouts.main')
@section('page-title')
    {{ __('Manage Book') }}
@endsection
@section('title')
    {{ __('Book') }}
@endsection
@section('page-breadcrumb')
    {{ __('Book') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('book.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @can('book create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Book') }}"
                data-url="{{ route('book.create') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@section('content')
    <div class="row">
        @foreach ($books as $book)
            @php
                $articleCount = Modules\Internalknowledge\Entities\Article::where('book', $book->id)->count();
            @endphp
            <div class="col-md-6 col-xl-3 All">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            <h5 class="mb-0">
                                <a href="@can('book manage') {{ route('book.show', [$book->id]) }} @endcan"
                                    title="{{ $book->title }}" class="">{{ $book->title }}<i
                                        class="px-2 ti ti-eye"></i></a>

                            </h5>
                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('book edit')
                                        <a class="dropdown-item" data-ajax-popup="true" data-size="lg"
                                            data-title="{{ __('Edit Book') }}"
                                            data-url="{{ route('book.edit', [$book->id]) }}">
                                            <i class="ti ti-pencil"></i> <span>{{ __('Edit') }}</span>
                                        </a>
                                    @endcan
                                    @can('book delete')
                                        <form id="delete-form-{{ $book->id }}"
                                            action="{{ route('book.destroy', [$book->id]) }}" method="POST">
                                            @csrf
                                            <a href="#"
                                                class="dropdown-item text-danger delete-popup bs-pass-para show_confirm"
                                                data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $book->id }}">
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
                            <p class="mb-0"><b>{{ __('Due Date:') }}</b> {{ $book->created_at->format('Y-m-d') }}</p>
                        </div>
                        <p class="text-muted text-sm mt-3 description">{{ substr($book->description, 0, 150) }}
                            @if (strlen($book->description) > 150)
                                ...
                            @endif
                        </p>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted">{{ __('MEMBERS') }}</h6>
                                <div class="user-group mx-2">
                                    @php
                                        $userIds = explode(',', $book->user_id);
                                        $users = \App\Models\User::whereIn('id', $userIds)->get();
                                    @endphp

                                    @foreach ($users as $user)
                                        <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ $user->name }}"
                                            @if ($user->avatar) src="{{ get_file($user->avatar) }}"
                                            @else
                                                src="{{ get_file('avatar.png') }}" @endif
                                            class="rounded-circle" width="25" height="25">
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6 text-end">

                                <h6 class="text-muted">{{ __('ARTICLES') }}</h6>
                                <h6 class="mb-0">{{ $articleCount }}</h6>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
        @auth('web')
            @can('book create')
                <div class="col-md-3">
                    <a href="#" class="btn-addnew-project" data-ajax-popup="true" data-size="md"
                        data-title="{{ __('Create New Book') }}" data-url="{{ route('book.create') }}"
                        style="padding: 90px 10px;">
                        <div class="badge bg-primary proj-add-icon">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New Book') }}</h6>
                        <p class="text-muted text-center">{{ __('Click here to add New Book') }}</p>
                    </a>
                </div>
            @endcan
        @endauth
    </div>
@endsection
