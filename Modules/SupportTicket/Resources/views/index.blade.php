@extends('supportticket::layouts.master')
{{-- @section('page-title')
    {{ __('Create Ticket') }}
@endsection --}}

@section('content')
    <div class="auth-wrapper auth-v1">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">

            <nav class="navbar navbar-expand-md navbar-dark default dark_background_color">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="#">

                        <img src="{{ !empty(company_setting('logo_light', $workspace->created_by, $workspace->id)) ? get_file(company_setting('logo_light', $workspace->created_by, $workspace->id)) : get_file(!empty(admin_setting('logo_light', $workspace->created_by, $workspace->id)) ? admin_setting('logo_light') : 'uploads/logo/logo_light.png', $workspace->created_by, $workspace->id) }}{{ '?' . time() }}"
                            class="navbar-brand-img auth-navbar-brand" style="
                        max-width: 168px;">

                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">

                            @if (isset($faq) && $faq == 'on')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('faqs', $workspace->slug) }}">{{ __('FAQ') }}</a>
                                </li>
                            @endif
                            @if (isset($knowledge) && $knowledge == 'on')
                                <li class="nav-item">
                                    <a class="nav-link"
                                        href="{{ route('knowledge', $workspace->slug) }}">{{ __('Knowledge') }}</a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link"
                                    href="{{ route('get.ticket.search', $workspace->slug) }}">{{ __('Search Ticket') }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="row align-items-center justify-content-center text-start">
                <div class="col-xl-12 text-center">
                    <div class="mx-3 mx-md-5 mt-3">

                    </div>
                    @if (Session::has('create_ticket'))
                        <div class="alert alert-success">
                            <p>{!! session('create_ticket') !!}</p>

                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body w-100">
                            <div class="">
                                <h4 class="text-primary mb-3">{{ __('Create Ticket') }}</h4>
                            </div>
                            <form method="post" action="{{ route('ticket.store', $workspace->slug) }}" class="create-form"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="text-start row">
                                    @if (!$fields->isEmpty())
                                        @include('supportticket::formBuilder')
                                    @endif
                                    <div class="text-center">
                                        <div class="d-block ">
                                            <input type="hidden" name="status" value="In Progress" />
                                            <button class="btn btn-primary btn-block mt-2">
                                                {{ __('Create Ticket') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-muted">{{ env('FOOTER_TEXT') }}</p>
                        </div>
                        <div class="col-6 text-end">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row w-100 pb-2">
        <div class="col-sm-12 col-md-2 col-lg-2">
            <div class="fabs">
                <div class="chat d-none">
                    <div class="chat_header btn-primary dark_background_color">
                        <div class="chat_option">

                            <span id="chat_head" class="">{{ __('Agent') }}</span>
                        </div>
                    </div>
                    <div class="msg_chat">
                        <div id="chat_fullscreen" class="chat_conversion chat_converse">
                            <h3 class="text-center mt-5 pt-5">{{ __('No Message Found.!') }}</h3>
                        </div>
                        <div class="fab_field">
                            <textarea id="chatSend" name="chat_message" placeholder="{{ __('Send a message') }}" class="chat_field chat_message"></textarea>
                        </div>
                    </div>
                    <div class="msg_form">
                        <div id="chat_fullscreen" class="chat_conversion chat_converse">
                            <form class="pt-4" name="chat_form">
                                <div class="form-group row mb-3 ml-md-2">
                                    <div class="col-sm-12 col-md-12">

                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" id="chat_email" name="name"
                                                    placeholder="{{ __('Enter You Email') }}" autofocus>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback d-block e_error">
                                        </div>
                                    </div>ticket.se="button"><span>{{ __('Start Chat') }}</span></button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    </div>
@endsection

@push('script')
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
<script>
    if ($(".summernote").length) {
    setTimeout(function () {
        $('.summernote').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
            ]
        });
    },100);
}
</script>
@endpush
