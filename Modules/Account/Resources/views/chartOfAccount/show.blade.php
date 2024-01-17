@extends('layouts.main')
@section('page-title')
    {{__('Account Drilldown Report')}}
@endsection
@section('page-breadcrumb')
    {{__('Chart of Account')}},
    {{__('Account Drilldown Report')}},
    {{ucwords($account->code. ' - ' .$account->name)}}
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('chart-of-account.show',$account->id),'method' => 'GET','id'=>'report_drilldown')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}
                                            {{ Form::date('start_date',$filter['startDateRange'], array('class' => 'month-btn form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}
                                            {{ Form::date('end_date',$filter['endDateRange'], array('class' => 'month-btn form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('account', __('Account'),['class'=>'form-label']) }}
                                            {{ Form::select('account',$accounts,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control select')) }}                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">
                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('report_drilldown').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{route('chart-of-account.show',$account->id)}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
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



    <div id="printableArea">
        <div class="row mt-2">
            <div class="col-3">
                {{--                <input type="hidden" value="{{__('Ledger').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">--}}
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{__('Report')}} :</h6>
                    <h7 class="text-sm mb-0">{{__('Account Drilldown')}}</h7>
                </div>
            </div>

            @if(!empty($account))
                <div class="col-3">
                    <div class="card p-4 mb-4">
                        <h6 class="mb-0">{{__('Account Name')}} :</h6>
                        <h7 class="text-sm mb-0">{{$account->name}}</h7>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card p-4 mb-4">
                        <h6 class="mb-0">{{__('Account Code')}} :</h6>
                        <h7 class="text-sm mb-0">{{$account->code}}</h7>
                    </div>
                </div>
            @endif

            <div class="col-3">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{__('Duration')}} :</h6>
                    <h7 class="text-sm mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h7>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th> {{__('Account Name')}}</th>
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Transaction Type')}}</th>
                                    <th> {{__('Transaction Date')}}</th>
                                    <th> {{__('Debit')}}</th>
                                    <th> {{__('Credit')}}</th>
                                    <th> {{__('Balance')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $balance     = 0;
                                    $totalDebit  = 0;
                                    $totalCredit = 0;
                                    $chartDatas  = \Modules\Account\Entities\AccountUtility::getAccountData($account->id,$filter['startDateRange'],$filter['endDateRange']);
                                    $accountName = \Modules\Account\Entities\ChartOfAccount::find($account->id);
                                @endphp

                                @foreach($chartDatas['invoice'] as $invoiceData)
                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        @php
                                            $invoice = \App\Models\Invoice::where('id',$invoiceData->invoice_id)->first();
                                        @endphp
                                        <td>{{ (!empty($invoice->customer)?$invoice->customer->name:'-')}}</td>
                                        <td>{{\App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id)}}</td>
                                        <td>{{$invoiceData->created_at->format('d-m-Y')}}</td>
                                        <td>-</td>

                                        @php
                                            $total= $invoiceData->price * $invoiceData->quantity;
                                            $balance +=$total;
                                            $totalCredit+=$total;
                                        @endphp
                                        <td>{{currency_format_with_sym($total)}}</td>
                                        <td>{{currency_format_with_sym($balance)}}</td>
                                    </tr>
                                @endforeach

                                @foreach($chartDatas['invoicepayment'] as $invoicePaymentData)
                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        @php
                                            $invoice = \App\Models\Invoice::where('id',$invoicePaymentData->invoice_id)->first();
                                        @endphp
                                        <td>{{ (!empty($invoice->customer)?$invoice->customer->name:'-')}}</td>
                                        <td>{{\App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id)}} {{__(' Manually Payment')}}</td>
                                        <td>{{$invoicePaymentData->created_at->format('d-m-Y')}}</td>
                                        <td>-</td>
                                        <td>{{currency_format_with_sym($invoicePaymentData->amount)}}</td>
                                        @php
                                            $balance += $invoicePaymentData->amount;
                                            $totalCredit+=$invoicePaymentData->amount;
                                        @endphp
                                        <td>{{currency_format_with_sym($balance)}}</td>
                                    </tr>
                                @endforeach

                                @foreach($chartDatas['revenue'] as $revenueData)
                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        <td>{{ (!empty($revenueData->customer)?$revenueData->customer->name:'-')}}</td>
                                        <td>{{__('Revenue')}}</td>
                                        <td>{{$revenueData->created_at->format('d-m-Y')}}</td>
                                        <td>-</td>
                                        <td>{{currency_format_with_sym($revenueData->amount)}}</td>
                                        @php
                                            $balance += $revenueData->amount;
                                            $totalCredit+=$revenueData->amount;
                                        @endphp
                                        <td>{{currency_format_with_sym($balance)}}</td>
                                    </tr>
                                @endforeach


                                @foreach($chartDatas['bill'] as $billProduct)
                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        @php

                                        $bill = Modules\Account\Entities\Bill::find($billProduct->bill_id);
                                        $vendor = \Modules\Account\Entities\Vender::find(!empty($bill)?$bill->vendor_id:'');
                                        @endphp
                                        <td>{{!empty($vendor)?$vendor->name : '-'}}</td>
                                        <td>{{Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id)}}</td>
                                        <td>{{$billProduct->created_at->format('d-m-Y')}}</td>
                                        <td>-</td>

                                        @php
                                            $total= $billProduct->price * $billProduct->quantity;
                                            $balance +=$total;
                                            $totalCredit+=$total;
                                        @endphp
                                        <td>{{currency_format_with_sym($total)}}</td>
                                        <td>{{currency_format_with_sym($balance)}}</td>
                                    </tr>
                                @endforeach

                                @foreach($chartDatas['billdata'] as $billData)
                                    @php
                                        $bill = Modules\Account\Entities\Bill::find($billData->ref_id);
                                        $vendor = \Modules\Account\Entities\Vender::find(!empty($bill)?$bill->vendor_id:'');
                                    @endphp
                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        <td>{{!empty($vendor)?$vendor->name : '-'}}</td>
                                        @if(!empty($bill->bill_id))
                                            <td>{{Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id)}}</td>
                                        @else
                                            <td>-</td>
                                        @endif

                                        <td>{{$billData->created_at->format('d-m-Y')}}</td>
                                        <td>{{currency_format_with_sym($billData->price)}}</td>
                                        <td>-</td>
                                        @php
                                            $balance += $billData->price;
                                            $totalDebit+= $billData->price;
                                        @endphp
                                        <td>{{currency_format_with_sym($totalCredit - $totalDebit)}}</td>
                                    </tr>
                                @endforeach

                                @foreach($chartDatas['billpayment'] as $billPaymentData)
                                    @php
                                        $bill = Modules\Account\Entities\BillPayment::where('bill_id',$billPaymentData->bill_id)->first();
                                        $billId= Modules\Account\Entities\Bill::find($billPaymentData->bill_id);
                                        $vendor = \Modules\Account\Entities\Vender::find($billId->vendor_id);
                                    @endphp
                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        <td>{{!empty($vendor)?$vendor->name : '-'}}</td>
                                        <td>{{Modules\Account\Entities\Bill::billNumberFormat($billId->bill_id)}}{{__(' Manually Payment')}}</td>
                                        <td>{{$billPaymentData->created_at->format('d-m-Y')}}</td>
                                        <td>{{currency_format_with_sym($billPaymentData->amount)}}</td>
                                        <td>-</td>
                                        @php
                                            $balance += $billPaymentData->amount;
                                            $totalDebit+= $billPaymentData->amount;
                                        @endphp
                                        <td>{{currency_format_with_sym($totalCredit-$totalDebit)}}</td>
                                    </tr>
                                @endforeach

                                @foreach($chartDatas['payment'] as $paymentData)

                                    @php
                                        $vendor = \Modules\Account\Entities\Vender::find($paymentData->vendor_id);
                                    @endphp
                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        <td>{{!empty($vendor)?$vendor->name : '-'}}</td>
                                        <td>{{__('Payment')}}</td>
                                        <td>{{$paymentData->created_at->format('d-m-Y')}}</td>

                                        <td>{{currency_format_with_sym($paymentData->amount)}}</td>
                                        <td>-</td>
                                        @php
                                            $balance += $paymentData->amount;
                                            $totalDebit += $paymentData->amount;
                                        @endphp
                                        <td>{{currency_format_with_sym($totalCredit-$totalDebit)}}</td>
                                    </tr>
                                @endforeach


{{--                                @dd($totalDebit+$totalCredit)--}}


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
