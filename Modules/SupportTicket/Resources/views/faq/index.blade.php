@extends('layouts.main')

@section('page-title')
    {{ __('Manage FAQ') }}
@endsection
@section('page-breadcrumb')
    {{ __('Support Ticket') }},{{ __('FAQ') }}
@endsection
@section('page-action')
    <div class="">
        @permission('faq import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Faq Import') }}"
                data-url="{{ route('faq.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        @permission('faq create')
            <a data-url="{{ route('support-ticket-faq.create') }}" data-size="lg" title="{{ __('Create') }}"
                data-title="{{ __('Create FAQ') }}" data-ajax-popup="true" class="btn btn-sm btn-primary btn-icon"
                data-bs-toggle="tooltip" data-bs-placement="top" title=""><i class="ti ti-plus text-white"></i>
            </a>
        @endpermission
    </div>
@endsection
@push('css')
    <style>
        .faq_desc {
            white-space: break-spaces !important;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="table-responsive">
                        <table id="pc-dt-simple" class="table pc-dt-simple">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th class="w-25">{{ __('Title') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    @if (Laratrust::hasPermission('faq edit') || Laratrust::hasPermission('faq delete'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($faqs as $index => $faq)
                                    <tr>
                                        <th scope="row">{{ ++$index }}</th>
                                        <td><span class="font-weight-bold white-space">{{ $faq->title }}</span></td>
                                        <td class="faq_desc">{!! strip_tags($faq->description) !!}</td>
                                        @if (Laratrust::hasPermission('faq edit') || Laratrust::hasPermission('faq delete'))
                                            <td>
                                                @permission('faq edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a data-size="lg" data-title="{{ __('Edit FAQ') }}"
                                                            data-ajax-popup="true"
                                                            data-url="{{ route('support-ticket-faq.edit', $faq->id) }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-toggle="tooltip" title="{{ __('Edit') }}"><span
                                                                class="text-white"> <i class="ti ti-pencil"></i></span></a>
                                                    </div>
                                                @endpermission
                                                @permission('faq delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        <form method="POST"
                                                            action="{{ route('support-ticket-faq.destroy', $faq->id) }}"
                                                            id="user-form-{{ $faq->id }}">
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
