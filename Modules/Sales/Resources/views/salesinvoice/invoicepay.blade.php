@extends('sales::layouts.invoicepayheader')
@section('page-title')
    {{ __('Sales Invoice Detail') }}
@endsection

@section('action-btn')
    <a href="{{ route('salesinvoice.pdf', \Crypt::encrypt($invoice->id)) }}" target="_blank"
        class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('Print') }}">
        <span class="btn-inner--icon text-white"><i class="ti ti-printer"></i>{{ __('Print') }}</span>
    </a>

    @if ($invoice->getDue() > 0)
    <a id="paymentModals"  class="btn btn-sm btn-primary">
        <span class="btn-inner--icon text-white"><i class="ti ti-credit-card"></i></span>
        <span class="btn-inner--text text-white">{{ __(' Pay Now') }}</span>
    </a>
    @endif
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <div class="col-12">
                            <div class="row align-items-center mb-2">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{ __('Invoice') }}</h6>

                                    @if ($invoice->status == 0)
                                        <span
                                            class="badge bg-secondary p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 1)
                                        <span
                                            class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 2)
                                        <span
                                            class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 3)
                                        <span
                                            class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                    @elseif($invoice->status == 4)
                                        <span
                                            class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-8">
                                    <h6 class="d-inline-block m-0 d-print-none">{{ __('Invoice ID') }}</h6>
                                    <span class="col-sm-8"><span
                                            class="text-sm">{{ Modules\Sales\Entities\SalesInvoice::invoiceNumberFormat($invoice->invoice_id, $invoice->created_by,$invoice->workspace) }}</span></span>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{ __('Assigned User :') }}</h6>
                                    <span
                                        class="text-sm">{{ !empty($invoice->assign_user) ? $invoice->assign_user->name : '' }}</span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-lg-6 col-md-8">
                                    <h6 class="d-inline-block m-0 d-print-none">{{ __('Invoice Date') }}</h6>
                                    <span class="col-sm-8"><span
                                            class="text-sm">{{ company_date_formate($invoice->created_at, $invoice->created_by,$invoice->workspace) }}</span></span>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{ __('Created :') }}</h6>
                                    <span
                                        class="text-sm">{{ company_date_formate($invoice->created_at, $invoice->created_by,$invoice->workspace) }}</span>
                                </div>
                            </div>
                            @if(!empty($customFields) && count($invoice->customField)>0)
                                @foreach($customFields as $field)
                                    <div class="col text-md-end">
                                        <small>
                                            <strong>{{$field->name}} :</strong><br>
                                            {{!empty($invoice->customField[$field->id])?$invoice->customField[$field->id]:'-'}}
                                            <br><br>
                                        </small>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('From') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Address') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_address'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Company City') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_city'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_zipcode'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Country') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_country'] }}</span></dd>
                                    </dl>
                                </div>
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('Billing Address') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Address') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->billing_address }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Billing City') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->billing_city }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $invoice->billing_postalcode }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Country') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->billing_country }}</span>
                                        </dd>


                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ !empty($invoice->contacts->name) ? $invoice->contacts->name : '--' }}</span>
                                        </dd>
                                    </dl>
                                </div>
                                @if (company_setting('salesinvoice_shipping_display', $invoice->created_by,$invoice->workspace) == 'on')
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('Shipping Address') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Address') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->shipping_address }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Shipping City') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->shipping_city }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $invoice->shipping_postalcode }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Country') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->shipping_country }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ !empty($invoice->contacts->name) ? $invoice->contacts->name : '--' }}</span>
                                        </dd>
                                    </dl>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <h5 class="px-2 py-2"><b>{{ __('Item List') }}</b></h5>
                                    <div class="table-responsive mt-4">
                                        <table class="table invoice-detail-table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Item') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Discount') }}</th>
                                                    <th>{{ __('Tax') }}</th>
                                                    <th>{{ __('Description') }}</th>
                                                    <th class="text-end">{{ __('Price') }}</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalQuantity = 0;
                                                    $totalRate = 0;
                                                    $totalAmount = 0;
                                                    $totalTaxPrice = 0;
                                                    $totalDiscount = 0;
                                                    $taxesData = [];
                                                @endphp

                                                @foreach ($invoice->items as $invoiceitem)
                                                    @php
                                                        $taxes = \Modules\Sales\Entities\SalesUtility::tax($invoiceitem->tax);
                                                        $totalQuantity += $invoiceitem->quantity;
                                                        $totalRate += $invoiceitem->price;
                                                        $totalDiscount += $invoiceitem->discount;
                                                        if (!empty($taxes[0])) {
                                                            foreach ($taxes as $taxe) {
                                                                $taxDataPrice = \Modules\Sales\Entities\SalesUtility::taxRate($taxe->rate, $invoiceitem->price, $invoiceitem->quantity,$invoiceitem->discount);
                                                                if (array_key_exists($taxe->name, $taxesData)) {
                                                                    $taxesData[$taxe->name] = $taxesData[$taxe->name] + $taxDataPrice;
                                                                } else {
                                                                    $taxesData[$taxe->name] = $taxDataPrice;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ !empty($invoiceitem->items()) ? $invoiceitem->items()->name : '' }}
                                                        </td>
                                                        <td>{{ $invoiceitem->quantity }} </td>
                                                        <td>{{ currency_format_with_sym($invoiceitem->price, $invoice->created_by,$invoice->workspace) }}
                                                        </td>
                                                        <td>{{ currency_format_with_sym($invoiceitem->discount, $invoice->created_by,$invoice->workspace) }}
                                                        <td>
                                                            <div class="col">
                                                                @php
                                                                    $taxPrice = 0;
                                                                @endphp
                                                                @if (!empty($invoiceitem->tax))
                                                                    @foreach ($invoiceitem->tax($invoiceitem->tax) as $tax)
                                                                        @php
                                                                            $taxPrice = \Modules\Sales\Entities\SalesUtility::taxRate($tax->rate, $invoiceitem->price, $invoiceitem->quantity,$invoiceitem->discount);
                                                                            $totalTaxPrice += $taxPrice;
                                                                        @endphp
                                                                        <a href="#!"
                                                                            class="d-block text-sm text-muted">{{ $tax->name . ' (' . $tax->rate . '%)' }}
                                                                            &nbsp;&nbsp;{{ currency_format_with_sym($taxPrice, $invoice->created_by,$invoice->workspace) }}</a>
                                                                    @endforeach
                                                                @else
                                                                    <a href="#!"
                                                                        class="d-block text-sm text-muted">{{ __('No Tax') }}</a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        </td>
                                                        <td style="white-space: break-spaces;">{{ $invoiceitem->description }} </td>
                                                        <td class="text-end">
                                                            {{ currency_format_with_sym(($invoiceitem->price * $invoiceitem->quantity)-$invoiceitem->discount+$taxPrice, $invoice->created_by,$invoice->workspace) }}
                                                        </td>

                                                        @php
                                                            $totalQuantity += $invoiceitem->quantity;
                                                            $totalRate += $invoiceitem->price;
                                                            $totalDiscount += $invoiceitem->discount;
                                                            $totalAmount += $invoiceitem->price * $invoiceitem->quantity;
                                                        @endphp
                                                    </tr>
                                                @endforeach
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td></td>
                                                    <td class="text-end"><strong>{{ __('Sub Total') }}</strong></td>
                                                    <td class="text-end subTotal">
                                                        {{ currency_format_with_sym($invoice->getSubTotal(), $invoice->created_by,$invoice->workspace) }}
                                                    </td>

                                                </tr>

                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td></td>
                                                    <td class="text-end"><strong>{{ __('Discount') }}</strong></td>
                                                    <td class="text-end subTotal">
                                                        {{ currency_format_with_sym($invoice->getTotalDiscount(), $invoice->created_by,$invoice->workspace) }}
                                                    </td>

                                                </tr>
                                                @if (!empty($taxesData))
                                                    @foreach ($taxesData as $taxName => $taxPrice)
                                                        @if ($taxName != 'No Tax')
                                                            <tr>
                                                                <td colspan="4"></td>
                                                                <td></td>
                                                                <td class="text-end"><b>{{ $taxName }}</b></td>
                                                                <td class="text-end">
                                                                    {{ currency_format_with_sym($taxPrice, $invoice->created_by,$invoice->workspace) }}
                                                                </td>

                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td colspan="4">&nbsp;</td>
                                                    <td></td>
                                                    <td class="text-end"><strong>{{ __('Total') }}</strong></td>
                                                    <td class="text-end subTotal">
                                                        {{ currency_format_with_sym($invoice->getTotal(), $invoice->created_by,$invoice->workspace) }}
                                                    </td>

                                                </tr>
                                            </tfoot>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card my-5 bg-secondary">
                                        <div class="card-body">
                                            <div class="row justify-content-between align-items-center">
                                                <div class="col-md-6 order-md-2 mb-4 mb-md-0">
                                                    <div class="d-flex align-items-center justify-content-md-end">
                                                        <span
                                                            class="h6 text-muted d-inline-block mr-3 mb-0">{{ __('Total value') }}:</span>
                                                        <span
                                                            class="h4 mb-0">{{ currency_format_with_sym($invoice->getTotal(), $invoice->created_by,$invoice->workspace) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 order-md-1">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Payments') }}</h5>
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table ">
                            <tr>
                                <th class="text-dark">{{ __('Transaction ID') }}</th>
                                <th class="text-dark">{{ __('Date') }}</th>
                                <th class="text-dark">{{ __('Payment Type') }}</th>
                                <th class="text-dark">{{ __('Receipt') }}</th>
                                <th class="text-dark">{{ __('Note') }}</th>
                                <th class="text-right">{{__('Amount')}}</th>
                            </tr>
                            @forelse($invoice->payments as $key =>$payment)
                                <tr>
                                    <td>{{ sprintf("%05d", $payment->transaction_id) }}</td>
                                    <td>{{ company_date_formate($payment->date, $invoice->created_by,$invoice->workspace) }}</td>
                                    <td>{{ $payment->payment_type }}</td>
                                    <td>@if($payment->payment_type == 'STRIPE')
                                        <a href="{{($payment->receipt)}}" target="_blank">
                                            <i class="ti ti-file"></i>
                                        </a>
                                    @elseif($payment->payment_type == 'Bank Transfer')
                                        <a href="{{ !empty($payment->receipt) ? (check_file($payment->receipt)) ? get_file($payment->receipt) : '#!' : '#!' }}" target="_blank" >
                                            <i class="ti ti-file"></i>
                                        </a>
                                    @endif
                                    </td>
                                    <td>{{!empty($payment->notes) ? $payment->notes : '-'}}</td>
                                    <td>{{currency_format_with_sym($payment->amount, $invoice->created_by,$invoice->workspace)}}</td>
                                </tr>
                            @empty
                                @include('layouts.nodatafound')
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($invoice->getDue() > 0)
    <div  id="paymentModal" class="modal" tabindex="-1" aria-labelledby="exampleModalLongTitle" aria-modal="true" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row pb-3 px-2">
                            <section class="">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    @if (company_setting('bank_transfer_payment_is_on', $invoice->created_by,$invoice->workspace) == 'on' &&
                                        !empty(company_setting('bank_number', $invoice->created_by,$invoice->workspace)) )
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-home-tab" data-bs-toggle="pill"
                                                data-bs-target="#bank-payment" type="button" role="tab"
                                                aria-controls="pills-home" aria-selected="true">{{ __('Bank trasfer') }}</a>
                                        </li>
                                    @endif
                                    @stack('salesinvoice_payment_tab')
                                </ul>


                                <div class="tab-content" id="pills-tabContent">
                                    @if (company_setting('bank_transfer_payment_is_on', $invoice->created_by,$invoice->workspace) == 'on' &&
                                        !empty(company_setting('bank_number', $invoice->created_by,$invoice->workspace)) )
                                        <div class="tab-pane fade " id="bank-payment" role="tabpanel"
                                            aria-labelledby="bank-payment">
                                            <form method="post" action="{{ route('invoice.pay.with.bank') }}"
                                                class="require-validation" id="payment-form" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="type" value="salesinvoice">
                                                <div class="row mt-2">
                                                    <div class="col-sm-8">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('Bank Details :') }}</label>
                                                            <p class="">
                                                                {!!company_setting('bank_number', $invoice->created_by,$invoice->workspace) !!}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="form-label">{{ __('Payment Receipt') }}</label>
                                                            <div class="choose-files">
                                                            <label for="payment_receipt">
                                                                <div class=" bg-primary "> <i class="ti ti-upload px-1"></i></div>
                                                                <input type="file" class="form-control" required="" accept="image/png, image/jpeg, image/jpg, .pdf" name="payment_receipt" id="payment_receipt" data-filename="payment_receipt" onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                            </label>
                                                            <p class="text-danger error_msg d-none">{{ __('This field is required')}}</p>

                                                            <img class="mt-2" width="70px"  id="blah3">
                                                        </div>
                                                            <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                                        </div>
                                                    </div>
                                                    <small class="text-danger">{{ __('first, make a payment and take a screenshot or download the receipt and upload it.')}}</small>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="amount">{{ __('Amount') }}</label>
                                                        <div class="input-group">
                                                            <span class="input-group-prepend"><span
                                                                    class="input-group-text">{{ !empty(company_setting('defult_currancy', $invoice->created_by,$invoice->workspace)) ? company_setting('defult_currancy', $invoice->created_by,$invoice->workspace) : '$' }}</span></span>
                                                            <input class="form-control" required="required"
                                                                min="0" name="amount" type="number"
                                                                value="{{ $invoice->getDue() }}" min="0"
                                                                step="0.01" max="{{ $invoice->getDue() }}"
                                                                id="amount">
                                                            <input type="hidden" value="{{ $invoice->id }}"
                                                                name="invoice_id">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="error" style="display: none;">
                                                            <div class='alert-danger alert'>
                                                                {{ __('Please correct the errors and try again.') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="button" class="btn  btn-light"
                                                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                    <button class="btn btn-primary"
                                                        type="submit">{{ __('Make Payment') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    @stack('salesinvoice_payment_div')
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endif
@endsection


@push('scripts')

    <script>
        $("#paymentModals").click(function(){
            $("#paymentModal").modal('show');
            $("ul li a").removeClass("active");
            $(".tab-pane").removeClass("active show");
            $("ul li:first a:first").addClass("active");
            $(".tab-pane:first").addClass("active show");
        });
    </script>

@endpush
