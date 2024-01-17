@extends('supportticket::layouts.master')
@section('page-title')
{{ __('Search Your Ticket') }}
@endsection
@section('content')
<div class="auth-wrapper auth-v1">
    <div class="bg-auth-side bg-primary"></div>
    <div class="auth-content">

        <nav class="navbar navbar-expand-md navbar-dark default dark_background_color">
            <div class="container-fluid">
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
                    <h2 class="mb-3 text-white f-w-600">{{ __('Knowledge') }}</h2>
                </div>

                <div class="text-start">
                    @if ($knowledges->count())
                        <div class="row">
                            @foreach ($knowledges as $index => $knowledge)
                                <div class="col-md-4">
                                    <div class="card" style="min-height: 200px;">
                                        <div class="card-header py-3 mb-3" id="heading-{{ $index }}"role="button"
                                        aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                            <div class="row m-auto">
                                                <h6 class="mr-3">{{ \Modules\SupportTicket\Entities\KnowledgeBase::knowlege_details($knowledge->category)}}  ( {{ \Modules\SupportTicket\Entities\KnowledgeBase::category_count($knowledge->category)}} ) </h6>
                                            </div>
                                        </div>
                                        <ul class="knowledge_ul">
                                            @foreach ($knowledges_detail as $details)
                                                @if ($knowledge->category == $details->category)
                                                    <li style="list-style: none;" class="child">
                                                        <a href="{{ route('knowledgedesc',[$workspace->slug,'id'=>$details->id])}}">
                                                            <i class="far fa-file-alt ms-3"></i>  {{ !empty($details->title) ? $details->title : '-' }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0 text-center">{{ __('No Knowledges found.') }}</h6>
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
@push('scripts')
    <script>
        // for Choose file
        $(document).on('change', 'input[type=file]', function () {
            var names = '';
            var files = $('input[type=file]')[0].files;

            for (var i = 0; i < files.length; i++) {
                names += files[i].name + '<br>';
            }
            $('.' + $(this).attr('data-filename')).html(names);
        });
    </script>
@endpush
