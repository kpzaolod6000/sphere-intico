@extends('layouts.main')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('Support-ticket') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-7">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body" style="height: 260px;">
                                    <div class="theme-avtar bg-danger">
                                        <i class="fas fa-link"></i>
                                    </div>
                                    <h6 class="mb-3 mt-4">
                                        {{ __(!empty($workspace) ? $workspace->name : 'Rajodiya infotech') }} </h6>
                                    <div class="stats my-4"><a href="#" class="btn btn-primary btn-sm text-sm cp_link"
                                            data-link="{{ route('support-ticket', $workspace->slug) }}"
                                            data-bs-whatever="{{ __('Ticket Link') }}" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Create Ticket') }}"
                                            title="{{ __('Click to copy link') }}">
                                            <i class="ti ti-link"></i>
                                            {{ __('Create Ticket') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body" style="height: 260px;">
                                    <div class="theme-avtar bg-primary">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 class="mb-3">{{ __('Categories') }}</h6>
                                    <h3 class="mb-0">{{ $categories }}</h3>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body" style="height: 260px;">
                                    <div class="theme-avtar bg-info">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Open') }}</p>
                                    <h6 class="mb-3">{{ __('Tickets') }}</h6>
                                    <h3 class="mb-0">{{ $open_ticket }} </h3>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body" style="height: 260px;">
                                    <div class="theme-avtar bg-warning">
                                        <i class="fas fa-ticket-alt"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Closed') }}</p>
                                    <h6 class="mb-3">{{ __('Tickets') }}</h6>
                                    <h3 class="mb-0">{{ $close_ticket }}</h3>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-xxl-5" style="flex: 0 0 auto; width: 39.66667%;">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Tickets by Category') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div id="categoryPie"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('This Year Tickets') }}</h5>
                            </div>
                            <div class="card-body">
                                <div id="chartBar"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>

    <script>
        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('Success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>

    <script>
        (function() {
            var categoryPieOptions = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($chartData['value']) !!},
                colors: {!! json_encode($chartData['color']) !!},
                labels: {!! json_encode($chartData['name']) !!},
                legend: {
                    show: true
                }
            };
            var categoryPieChart = new ApexCharts(document.querySelector("#categoryPie"), categoryPieOptions);
            categoryPieChart.render();
        })();

        (function() {
            var chartBarOptions = {
                series: [{
                    name: '{{ __('Tickets') }}',
                    data: {!! json_encode(array_values($monthData)) !!}
                }, ],

                chart: {
                    height: 150,
                    type: 'area',

                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: {!! json_encode(array_keys($monthData)) !!},
                    title: {
                        text: '{{ __('Months') }}'
                    }
                },
                colors: ['#ffa21d', '#FF3A6E'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },

                yaxis: {
                    title: {
                        text: '{{ __('Tickets') }}'
                    },
                    tickAmount: 3,
                    min: 1,
                    max: 30,
                }
            };
            var arChart = new ApexCharts(document.querySelector("#chartBar"), chartBarOptions);
            arChart.render();
        })();

        (function() {
            var chartBarOptions = {
                series: [{
                    name: 'Order',
                    data: {!! json_encode($chartDatas['data']) !!},
                }, ],
                chart: {
                    height: 250,
                    type: 'area',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: ["15-Jun", "16-Jun", "17-Jun", "18-Jun", "19-Jun", "20-Jun", "21-Jun"],
                    title: {
                        text: ''
                    }
                },
                colors: ['#1260CC'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    title: {
                        text: ''
                    },

                }
            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();
    </script>
@endpush
