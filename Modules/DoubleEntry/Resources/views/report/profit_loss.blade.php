@extends('layouts.main')
@section('page-title')
    {{ __('Profit & Loss') }}
@endsection


@section('page-breadcrumb')
    {{__('Profit and Loss')}}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/DoubleEntry/Resources/assets/css/app.css') }}" id="main-style-link">
@endpush


@push('scripts')
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

    <script src="{{ asset('Modules/DoubleEntry/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>

    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#filter").click(function() {
                $("#show_filter").toggle();
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            callback();
            function callback() {
                var start_date = $(".startDate").val();
                var end_date = $(".endDate").val();

                $('.start_date').val(start_date);
                $('.end_date').val(end_date);

            }
        });

    </script>


@endpush

@section('page-action')
    <div>
        {{ Form::open(['route' => ['profit.loss.print'],'class' => 'custom-action-btn me-2']) }}
        <input type="hidden" name="start_date" class="start_date">
        <input type="hidden" name="end_date" class="end_date">
        <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Print') }}"
                data-original-title="{{ __('Print') }}"><i class="ti ti-printer"></i></button>
        {{ Form::close() }}

        <div class="float-end" id="filter">
            <button id="filter" class="btn btn-sm btn-primary"><i class="ti ti-filter"></i></button>
        </div>

        <div class="float-end me-2">
            <a href="{{ route('report.profit.loss' , 'horizontal')}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Horizontal View') }}"
               data-original-title="{{ __('Horizontal View') }}"><i class="ti ti-separator-vertical"></i></a>
        </div>
    </div>
@endsection


@section('content')
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card" id="show_filter" style="display:none;">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.profit.loss'], 'method' => 'GET', 'id' => 'report_profit_loss']) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', $filter['startDateRange'], ['class' => 'startDate form-control']) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', $filter['endDateRange'], ['class' => 'endDate form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-primary"
                                           onclick="document.getElementById('report_profit_loss').submit(); return false;"
                                           data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                           data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="{{ route('report.profit.loss') }}" class="btn btn-sm btn-danger "
                                           data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                           data-original-title="{{ __('Reset') }}">
                                            <span class="btn-inner--icon">
                                                <i class="ti ti-trash-off text-white-off "></i></span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    @php
        $authUser = creatorId();
        $user = App\Models\User::find($authUser);
    @endphp

    <div class="row justify-content-center" id="printableArea">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="account-main-title mb-5">
                        <h5>{{ 'Profit & Loss of ' . $user->name . ' as of ' . $filter['startDateRange'] . ' to ' . $filter['endDateRange'] }}
                            </h5>
                    </div>
                    <div class="account-title d-flex align-items-center justify-content-between border-top border-bottom py-2">
                        <h6 class="mb-0">{{ __('Account') }}</h6>
                        <h6 class="mb-0 text-center">{{ _('Account Code') }}</h6>
                        <h6 class="mb-0 text-end">{{ __('Total') }}</h6>
                    </div>
                    @php
                        $totalIncome = 0;
                        $netProfit = 0;
                        $totalCosts = 0;
                        $grossProfit= 0;
                    @endphp


                    @foreach ($chartAccounts as $accounts)
                        @if ($accounts['Type'] == 'Income')
                            <div class="account-main-inner border-bottom py-2">
                                <p class="fw-bold mb-2">{{ $accounts['Type'] }}</p>

                                @foreach ($accounts['account'] as $key => $record)
                                    @php
                                        if($record['netAmount'] > 0)
                                        {
                                            $net_amount = $record['netAmount'];
                                        }
                                        else
                                        {
                                            $net_amount= - $record['netAmount'];
                                        }
                                    @endphp
                                    <div class="account-inner d-flex align-items-center justify-content-between">
                                        @if (!preg_match('/\btotal\b/i', $record['account_name']))
                                            <p class="mb-2 ps-3"><a
                                                        href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                        class="text-primary">{{ $record['account_name'] }}</a>
                                            </p>
                                        @else
                                            <p class="fw-bold mb-2"><a
                                                        href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                        class="text-dark">{{ $record['account_name'] }}</a>
                                        @endif
                                        <p class="mb-2 text-center">{{ $record['account_code'] }}</p>
                                        <p class="text-primary mb-2 float-end text-end">
                                            {{ currency_format_with_sym($net_amount) }}</p>
                                    </div>

                                    @php
                                        if ($record['account_name'] === 'Total Income') {
                                            $totalIncome = $net_amount;
                                        }

                                        if ($record['account_name'] == 'Total Costs of Goods Sold') {
                                            $totalCosts = $net_amount;
                                        }
                                        $grossProfit = $totalIncome - $totalCosts;
                                    @endphp
                                @endforeach
                            </div>
                        @endif

                        @if ($accounts['Type'] == 'Costs of Goods Sold')
                                <div class="account-main-inner border-bottom py-2">
                                    <p class="fw-bold mb-2">{{ $accounts['Type'] }}</p>

                                    @foreach ($accounts['account'] as $key => $record)
                                        @php
                                            if($record['netAmount'] > 0)
                                            {
                                                $net_amount = $record['netAmount'];
                                            }
                                            else
                                            {
                                                $net_amount= - $record['netAmount'];
                                            }
                                        @endphp
                                        <div class="account-inner d-flex align-items-center justify-content-between">
                                            @if (!preg_match('/\btotal\b/i', $record['account_name']))
                                                <p class="mb-2 ps-3"><a
                                                            href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                            class="text-primary">{{ $record['account_name'] }}</a>
                                                </p>
                                            @else
                                                <p class="fw-bold mb-2"><a
                                                            href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                            class="text-dark">{{ $record['account_name'] }}</a>
                                            @endif
                                            <p class="mb-2 text-center">{{ $record['account_code'] }}</p>
                                            <p class="text-primary mb-2 float-end text-end">
                                                {{ currency_format_with_sym($net_amount) }}</p>
                                        </div>

                                        @php
                                            if ($record['account_name'] === 'Total Income') {
                                                $totalIncome = $net_amount;
                                            }

                                            if ($record['account_name'] == 'Total Costs of Goods Sold') {
                                                $totalCosts = $net_amount;
                                            }
                                            $grossProfit = $totalIncome - $totalCosts;
                                        @endphp
                                    @endforeach
                                </div>
                            @endif
                    @endforeach

                    @if($grossProfit > 0)
                        <div class="account-inner d-flex align-items-center justify-content-between border-bottom">
                            <p></p>
                            <p class="fw-bold mb-2 text-center">{{ __('Gross Profit') }}</p>
                            <p class="text-primary mb-2 float-end text-end">
                                {{ currency_format_with_sym($grossProfit) }}</p>
                        </div>
                    @endif

                    @foreach ($chartAccounts as $accounts)
                        @if ($accounts['Type'] == 'Expenses')
                            <div class="account-main-inner border-bottom py-2">
                                <p class="fw-bold mb-2">{{ $accounts['Type'] }}</p>
                                @foreach ($accounts['account'] as $key => $record)
                                    @php
                                    if($record['netAmount'] > 0)
                                    {
                                        $net_amount = $record['netAmount'];
                                    }
                                    else
                                    {
                                        $net_amount= - $record['netAmount'];
                                    }
                                    @endphp
                                    <div class="account-inner d-flex align-items-center justify-content-between">
                                        @if (!preg_match('/\btotal\b/i', $record['account_name']))
                                            <p class="mb-2 ps-3"><a
                                                        href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                        class="text-primary">{{ $record['account_name'] }}</a>
                                            </p>
                                        @else
                                            <p class="fw-bold mb-2"><a
                                                        href="{{ route('report.ledger', $record['account_id']) }}?account={{ $record['account_id'] }}"
                                                        class="text-dark">{{ $record['account_name'] }}</a>
                                        @endif
                                        <p class="mb-2 text-center">{{ $record['account_code'] }}</p>
                                        <p class="text-primary mb-2 float-end text-end">
                                            {{ currency_format_with_sym($net_amount) }}</p>
                                    </div>

                                    @php

                                        if ($record['account_name'] === 'Total Expenses') {
                                            $totalIncome = $net_amount;
                                            $netProfit = $grossProfit - $net_amount;
                                        }
                                    @endphp
                                @endforeach
                            </div>

                            <div class="account-inner d-flex align-items-center justify-content-between border-bottom">
                                <p></p>
                                <p class="fw-bold mb-2 text-center">{{ __('Net Profit/Loss') }}</p>
                                <p class="text-primary mb-2 float-end text-end">
                                    {{ currency_format_with_sym($netProfit) }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
