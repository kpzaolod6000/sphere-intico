@extends('supportticket::layouts.master')
@section('page-title')
{{ __('FAQ') }}
@endsection
@section('content')
<div class="auth-wrapper auth-v1">
    <div class="bg-auth-side bg-primary"></div>
    <div class="auth-content">


        <nav class="navbar navbar-expand-md navbar-dark default dark_background_color">
            <div class="container-fluid pe-2">
                <a class="navbar-brand" href="#">
                    <img src="{{ get_file(light_logo()) }}{{'?'.time()}}" class="navbar-brand-img auth-navbar-brand" style="max-width: 110px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01" style="flex-grow: 0;">
                    <ul class="navbar-nav ms-auto me-auto mb-2 mb-lg-0">
                       
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('support-ticket',$workspace->slug) }}">{{ __('Create Ticket') }}</a>
                        </li>
                        @if(isset($faq) && $faq == 'on')
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('faqs',$workspace->slug)}}">{{ __('FAQ') }}</a>
                        </li>
                        @endif
                        @if(isset($knowledge) && $knowledge == 'on')
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('knowledge',$workspace->slug)}}">{{ __('Knowledge') }}</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('get.ticket.search',$workspace->slug) }}">{{ __('Search Ticket') }}</a>
                        </li>

                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row align-items-center justify-content-center text-start">
            <div class="col-xl-12 text-center">
                <div class="mx-3 mx-md-5">
                    <h2 class="mb-3 text-white f-w-600">{{ __('FAQ') }}</h2>
                </div>

                <div class="text-start faq">
                    @if ($faqs->count())
                        <div class="accordion accordion-flush" id="faq-accordion">
                            @foreach ($faqs as $index => $faq)
                                <div class="accordion-item card">
                                    <h2 class="accordion-header" id="heading-{{ $index }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $index }}">
                                            <span class="d-flex align-items-center">
                                                <i class="ti ti-info-circle text-primary"></i> {{ $faq->title }}
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $index }}"
                                        class="accordion-collapse collapse @if ($index == 0) show @endif" aria-labelledby="heading-{{ $index }}" data-bs-parent="#faq-accordion">
                                        <div class="accordion-body">
                                            <p class="mb-0">{!! $faq->description !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0 text-center">{{ __('No Faqs found.') }}</h6>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="auth-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted">{{env('FOOTER_TEXT')}}</p>
                    </div>
                    <div class="col-6 text-end">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
