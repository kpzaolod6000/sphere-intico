@extends('layouts.main')
@section('breadcrumb')
@endsection
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('page-breadcrumb')
    {{ __('Sales')}}
@endsection

@section('content')
<div class="row">

    <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                @if (\Auth::user()->type == 'company')
                <div class="col-xxl-7">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-user"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2"></p>
                                    <h6 class="mb-3">{{ __('Total User') }}</h6>
                                    <h3 class="mb-0">{{ $data['totalUser'] }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-success">
                                        <i class="ti ti-building"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2"></p>
                                    <h6 class="mb-3">{{ __('Total Account') }}</h6>
                                    <h3 class="mb-0">{{ $data['totalAccount'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-danger">
                                        <i class="fas fa-id-badge"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2"></p>
                                    <h6 class="mb-3">{{ __('Total Contact') }}</h6>
                                    <h3 class="mb-0">{{ $data['totalContact'] }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-info">
                                        <i class="ti ti-currency-dollar-singapore"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2"></p>
                                    <h6 class="mb-3">{{ __('Total Opportunities') }}</h6>
                                    <h3 class="mb-0">{{ $data['totalOpportunities'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-receipt"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2"></p>
                                    <h6 class="mb-3">{{ __('Total Invoices') }}</h6>
                                    <h3 class="mb-0">{{ $data['totalInvoice'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-file-invoice"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4"></p>
                                    <h6 class="mb-3">{{ __('Total Salesorder') }}</h6>
                                    <h3 class="mb-0">{{ $data['totalSalesorder'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if (\Auth::user()->type == 'company')
                 <div class="col-xxl-5">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('Invoice'.' '.'&'.' '.'Quote'.' '.'&'.' '.'Sales Order')}}</h5>
                        </div>
                        <div class="card-body adj_card">
                            <div id="traffic-chart"></div>
                        </div>
                    </div>
                </div>
                @endif
                @if (\Auth::user()->type != 'company')
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{__('Invoice'.' '.'&'.' '.'Quote'.' '.'&'.' '.'Sales Order')}}</h5>
                        </div>
                        <div class="card-body">
                            <div id="traffic-chart"></div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-xxl-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-xl-4 col-md-6">
                                    <div class="card" style="min-height:200px;">
                                        <div class="card-header">
                                            <h5>{{__('Invoice Overview')}}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="progress">
                                                @foreach($data['invoice'] as $invoice)
                                                    <div class="progress-bar bg-{{$data['invoiceColor'][$invoice['status']]}}" role="progressbar" style="width: {{$invoice['percentage']}}%" aria-valuenow="{{$invoice['percentage']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                @endforeach
                                            </div>
                                            <div class="row mt-3">
                                                @forelse($data['invoice'] as $invoice)
                                                    <div class="col-md-6 text-justify">
                                                        <span class="text-sm text-{{$data['invoiceColor'][$invoice['status']]}}">●</span>
                                                        <small>{{$invoice['data'].' '.__($invoice['status'])}} (<a href="#" class="text-sm text-muted">{{number_format($invoice['percentage'],'2')}}%</a>)</small>
                                                    </div>
                                                @empty
                                                    <div class="col-md-12 text-center mt-3">
                                                        <h6>{{__('Invoice record not found')}}</h6>
                                                    </div>
                                                @endforelse
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card" style="min-height:205px;">
                                        <div class="card-header">
                                            <h5>{{__('Quote Overview')}}</h5>
                                        </div>
                                        <div class="card-body">

                                            <div class="progress">
                                                @foreach($data['quote'] as $quote)
                                                    <div class="progress-bar bg-{{$data['invoiceColor'][$quote['status']]}}" role="progressbar" style="width: {{$quote['percentage']}}%" aria-valuenow="{{$quote['percentage']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                @endforeach
                                            </div>
                                            <div class="row mt-3">
                                                @forelse($data['quote'] as $quote)
                                                    <div class="col-md-6 text-justify">
                                                        <span class="text-sm text-{{$data['invoiceColor'][$quote['status']]}}">●</span>
                                                        <small>{{$quote['data'].' '.__($quote['status'])}} (<a href="#" class="text-sm text-muted">{{number_format($quote['percentage'],'2')}}%</a>)</small>
                                                    </div>
                                                @empty
                                                    <div class="col-md-12 text-center mt-3">
                                                        <h6>{{__('Quote record not found')}}</h6>
                                                    </div>
                                                @endforelse
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card" style="min-height:205px;">
                                        <div class="card-header">
                                            <h5>{{__('Sales Order Overview')}}</h5>
                                        </div>
                                        <div class="card-body">

                                            <div class="progress">
                                                @foreach($data['salesOrder'] as $salesOrder)
                                                    <div class="progress-bar bg-{{$data['invoiceColor'][$salesOrder['status']]}}" role="progressbar" style="width: {{$salesOrder['percentage']}}%" aria-valuenow="{{$salesOrder['percentage']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                                @endforeach
                                            </div>
                                            <div class="row mt-3">
                                                @forelse($data['salesOrder'] as $salesOrder)
                                                    <div class="col-md-6 text-justify">
                                                        <span class="text-sm text-{{$data['invoiceColor'][$salesOrder['status']]}}">●</span>
                                                        <small>{{$salesOrder['data'].' '.__($salesOrder['status'])}} (<a href="#" class="text-sm text-muted">{{number_format($salesOrder['percentage'],'2')}}%</a>)</small>
                                                    </div>
                                                @empty
                                                    <div class="col-md-12 text-center mt-3">
                                                        <h6>{{__('Invoice record not found')}}</h6>
                                                    </div>
                                                @endforelse
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xxl-12">
                    <div class="card card-fluid">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">{{__('Meeting Schedule')}}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="list-group overflow-auto list-group-flush dashboard-box">
                            @forelse($data['topMeeting'] as $meeting)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col ml-n2">
                                            <a href="#!" class=" h6 mb-0">{{$meeting->name}}</a>
                                            <div>
                                                <small>{{$meeting->description}}</small>
                                            </div>
                                        </div>
                                         <div class="col-auto">
                                            <span data-toggle="tooltip" data-title="{{__('Meetign Date')}}">{{$meeting->start_date}}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 text-center">
                                    <h6 class="m-3">{{__('Meeting schedule not found')}}</h6>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                    <!-- [ sample-page ] end -->
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
    <!-- [ Main Content ] end -->
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script>
    (function () {
        var options = {
            chart: {
                height: 250,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [
                {
                name: "{{__('Quote')}}",
                data:  {!! json_encode($data['lineChartData']['quoteAmount']) !!}
            }, {
                name: "{{__('Invoice')}}",
                data: {!! json_encode($data['lineChartData']['invoiceAmount']) !!}
            }, {
                name: "{{__('Sales Order')}}",
                 data: {!! json_encode($data['lineChartData']['salesorderAmount']) !!}
            }
            ],
            xaxis: {
                categories: {!! json_encode($data['lineChartData']['day']) !!},
                title: {
                    text: "{{__('Days')}}"
                }
            },
            colors: ['#453b85', '#FF3A6E', '#3ec9d6'],

            grid: {
                strokeDashArray: 2,
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
            },

            yaxis: {
                min: 100,
                title: {
                    text: '{{__('Amount')}}'
                },
            }
        };
        var chart = new ApexCharts(document.querySelector("#traffic-chart"), options);
        chart.render();
    })();
</script>

@endpush
