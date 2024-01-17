@extends('layouts.main')
@section('page-title')
    {{ __('Manage Book') }}
@endsection
@section('page-breadcrumb')
    {{ __('Book') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('book.grid') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
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
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th> {{ __('Title') }}</th>
                                    <th> {{ __('Description') }}</th>
                                    <th> {{ __('Assign User') }}</th>
                                    <th> {{ __('Article') }}</th>
                                    {{-- @if (Gate::check('revenue edit') || Gate::check('revenue delete')) --}}
                                    <th width="10%"> {{ __('Action') }}</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($books as $book)
                                    <tr class="font-style">
                                        <td>{{ $book->title }}</td>
                                        <td>{{ substr($book->description, 0, 50) }}
                                            @if (strlen($book->description) > 50)
                                                ...
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $userIds = explode(',', $book->user_id);
                                                $users = \App\Models\User::whereIn('id', $userIds)->get();
                                                $articleCount = Modules\Internalknowledge\Entities\Article::where('book', $book->id)->count();
                                            @endphp

                                            @foreach ($users as $user)
                                                <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $user->name }}"
                                                    @if ($user->avatar) src="{{ get_file($user->avatar) }}"
                                                    @else
                                                        src="{{ get_file('avatar.png') }}" @endif
                                                    class="rounded-circle" width="25" height="25">
                                            @endforeach
                                        </td>
                                        <td>{{ $articleCount }}</td>
                                        <td class="Action">
                                            <span>
                                                @can('book show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        {{-- <a data-size="md" data-url="{{ route('book.show', $book->id) }}"
                                                            data-ajax-popup="true" data-bs-toggle="tooltip"
                                                            data-title="{{ __('book Details') }}"title="{{ __('Details') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                            <i class="ti ti-eye"></i>
                                                        </a> --}}
                                                        <a href="{{ route('book.show', $book->id) }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                            value="{{ $articleCount }}" data-bs-toggle="tooltip"
                                                            data-title="{{ __('show article') }}"
                                                            title="{{ __('Show Article') }}"><i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('book edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a class="mx-3 btn btn-sm align-items-center"
                                                            data-url="{{ route('book.edit', $book->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="{{ __('Edit') }}" data-title="{{ __('Edit Book') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('book delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['book.destroy', $book->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $book->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
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
