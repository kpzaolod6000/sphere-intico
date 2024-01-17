@extends('layouts.main')
@section('page-title')
    {{ __('Sales Invoice') }}
@endsection
@section('title')
    {{ __('Invoice') }}
@endsection
@section('page-breadcrumb')
    {{ __('Sales Invoice') }},
    {{ __('Show') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('salesinvoice.pdf', \Crypt::encrypt($invoice->id)) }}" target="_blank"
            class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('Print') }}">
            <span class="btn-inner--icon text-white"><i class="ti ti-printer"></i></span>
        </a>

        @if (Auth::user()->type == 'company')
            <a class="btn btn-sm btn-warning btn-icon cp_link"
                data-link="{{ route('pay.salesinvoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)) }}"
                data-bs-toggle="tooltip" data-title="{{ __('Click to copy invoice link') }}"
                title="{{ __('Copy link') }}"><span class="btn-inner--icon text-white"><i class="ti ti-file"></i></span>
            </a>
        @endif

        @can('Create Invoice Payment')
            @if ($invoice->getDue() > 0)
                <a href="#" class="btn btn-sm btn-primary theme bg-primary btn-icon"
                    data-url="{{ route('invoices.payments.create', $invoice->id) }}" data-ajax-popup="true"
                    data-title="{{ __('Add Payment') }}" title="{{ __('Add Payment') }}" data-bs-toggle="tooltip">
                    <span class="btn-inner--icon text-white"><i class="ti ti-plus"></i></span>
                </a>
            @endif
        @endcan
        <a data-size="md" data-url="{{ route('salesinvoice.link', $invoice->id) }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" data-title="{{ __('Send Invoice Link') }}" title="{{ __('Send Link') }}"
            class="btn btn-sm btn-secondary btn-icon">
            <i class="ti ti-brand-telegram text-white"></i>
        </a>
        @can('salesinvoice edit')
            <a href="{{ route('salesinvoice.edit', $invoice->id) }}" class="btn btn-sm btn-info btn-icon"
                data-bs-toggle="tooltip" data-title="{{ __('invoice Edit') }}" title="{{ __('Edit') }}"><i
                    class="ti ti-pencil"></i>
            </a>
        @endcan
        @if (module_is_active('ProductService'))
            <a data-size="md" data-url="{{ route('salesinvoice.invoiceitem', $invoice->id) }}" data-ajax-popup="true"
                data-title="{{ __('Create New Invoice') }}" title="{{ __('Create') }}" data-bs-toggle="tooltip"
                class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endif
    </div>
@endsection
@section('content')

    <div class="row">

        <div class="col-lg-10">
            <!-- [ Invoice ] start -->
            <div class="container">
                <div>
                    <div class="card" id="printTable">
                        <div class="card-body">
                            <div class="row align-items-center mb-4">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <div class="col-lg-6 col-md-8">
                                        <h6 class="d-inline-block m-0 d-print-none">{{ __('Invoice ID') }}</h6>
                                        <span class="col-sm-8"><span
                                                class="text-sm">{{ Modules\Sales\Entities\SalesInvoice::invoiceNumberFormat($invoice->invoice_id) }}</span></span>
                                    </div>
                                    <div class="col-lg-6 col-md-8 mt-3">
                                        <h6 class="d-inline-block m-0 d-print-none">{{ __('Invoice Date') }}</h6>
                                        <span class="col-sm-8"><span
                                                class="text-sm">{{ company_date_formate($invoice->created_at) }}</span></span>
                                    </div>
                                    <h6 class="d-inline-block m-0 d-print-none mt-3">{{ __('Invoice') }}</h6>
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
                                <div class="col-sm-6 text-sm-end">
                                    <div>

                                        <div class="float-end mt-3">
                                            {!! DNS2D::getBarcodeHTML(
                                                route('pay.salesinvoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)),
                                                'QRCODE',
                                                2,
                                                2,
                                            ) !!}
                                        </div>

                                    </div>
                                </div>
                                @if (!empty($customFields) && count($invoice->customField) > 0)
                                    @foreach ($customFields as $field)
                                        <div class="col text-md-end">
                                            <small>
                                                <strong>{{ $field->name }} :</strong><br>
                                                @if ($field->type == 'attachment')
                                                    <a href="{{ get_file($invoice->customField[$field->id]) }}"
                                                        target="_blank">
                                                        <img src=" {{ get_file($invoice->customField[$field->id]) }} "
                                                            class="wid-75 rounded me-3">
                                                    </a>
                                                @else
                                                    {{ !empty($invoice->customField[$field->id]) ? $invoice->customField[$field->id] : '-' }}
                                                @endif
                                                <br><br>
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-sm-12 ">
                                    <h5 class="px-2 py-2"><b>{{ __('Item List') }}</b></h5>
                                    <div class="table-responsive mt-4">
                                        <table class="table invoice-detail-table">
                                            <thead>
                                                <tr class="thead-default">
                                                    <th>{{ __('Item') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>{{ __('Discount') }}</th>
                                                    <th>{{ __('Tax') }}</th>
                                                    <th>{{ __('Description') }}</th>
                                                    <th>{{ __('Price') }}</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalQuantity = 0;
                                                    $totalRate = 0;
                                                    $totalAmount = 0;
                                                    $totalTaxPrice = 0;
                                                    $totalDiscount = 0;
                                                    $TaxPrice_array = [];
                                                    $taxesData = [];
                                                @endphp
                                                @foreach ($invoice->items as $key => $invoiceitem)
                                                    @php
                                                        $taxes = Modules\Sales\Entities\SalesUtility::tax($invoiceitem->tax);
                                                        $totalQuantity += $invoiceitem->quantity;
                                                        $totalRate += $invoiceitem->price;
                                                        $totalDiscount += $invoiceitem->discount;
                                                        if (!empty($taxes[0])) {
                                                            foreach ($taxes as $taxe) {
                                                                $taxDataPrice = Modules\Sales\Entities\SalesUtility::taxRate($taxe->rate, $invoiceitem->price, $invoiceitem->quantity, $invoiceitem->discount);
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
                                                        <td>{{ currency_format_with_sym($invoiceitem->price) }} </td>
                                                        <td>{{ currency_format_with_sym($invoiceitem->discount) }} </td>
                                                        <td>
                                                            <div class="col">
                                                                @php
                                                                    $totalTaxPrice = 0;
                                                                    $data = 0;

                                                                @endphp
                                                                @if (!empty($invoiceitem->tax))
                                                                    @foreach ($invoiceitem->Tax($invoiceitem->tax) as $tax)
                                                                        @if (!empty($tax))
                                                                            @php
                                                                                $taxPrice = Modules\Sales\Entities\SalesUtility::taxRate($tax->rate, $invoiceitem->price, $invoiceitem->quantity, $invoiceitem->discount);
                                                                                $totalTaxPrice += $taxPrice;
                                                                                $data += $taxPrice;
                                                                            @endphp
                                                                            <a href="#!"
                                                                                class="d-block text-sm text-muted">{{ $tax->name . ' (' . $tax->rate . '%)' }}
                                                                                &nbsp;&nbsp;{{ currency_format_with_sym($taxPrice) }}</a>
                                                                        @endif
                                                                    @endforeach
                                                                    @php
                                                                        array_push($TaxPrice_array, $data);
                                                                    @endphp
                                                                @else
                                                                    <a href="#!"
                                                                        class="d-block text-sm text-muted">{{ __('No Tax') }}</a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td style="white-space: break-spaces;">
                                                            {{ $invoiceitem->description }} </td>
                                                        @php
                                                            $tr_tex = array_key_exists($key, $TaxPrice_array) == true ? $TaxPrice_array[$key] : 0;
                                                        @endphp
                                                        <td class="text-right">
                                                            {{ currency_format_with_sym($invoiceitem->price * $invoiceitem->quantity - $invoiceitem->discount + $tr_tex) }}
                                                        </td>
                                                        <td class="text-right">
                                                            @if (module_is_active('ProductService'))
                                                                @can('salesinvoice edit')
                                                                    <div class="action-btn bg-info ms-2">
                                                                        <a data-url="{{ route('salesinvoice.item.edit', $invoiceitem->id) }}"
                                                                            data-ajax-popup="true"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white "
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Edit') }}"
                                                                            data-title="{{ __('Edit Item') }}"><i
                                                                                class="ti ti-pencil"></i></a>
                                                                    </div>
                                                                @endcan
                                                            @endif
                                                            @can('salesinvoice delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['salesinvoice.items.delete', $invoiceitem->id]]) !!}
                                                                    <a href="#!"
                                                                        class="mx-3 btn btn-sm  align-items-center text-white show_confirm"
                                                                        data-bs-toggle="tooltip" title='Delete'>
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            @endcan
                                                        </td>
                                                        @php
                                                            $totalQuantity += $invoiceitem->quantity;
                                                            $totalRate += $invoiceitem->price;
                                                            $totalDiscount += $invoiceitem->discount;
                                                            $totalAmount += $invoiceitem->price * $invoiceitem->quantity;
                                                        @endphp
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="invoice-total">
                                        <table class="table invoice-table ">
                                            <tbody>
                                                <tr>
                                                    <th>{{ __('Sub Total ') }}:</th>
                                                    <td>{{ currency_format_with_sym($invoice->getSubTotal()) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Discount') }} :</th>
                                                    <td>{{ currency_format_with_sym($invoice->getTotalDiscount()) }}</td>
                                                </tr>
                                                @if (!empty($taxesData))
                                                    @foreach ($taxesData as $taxName => $taxPrice)
                                                        @if ($taxName != 'No Tax')
                                                            <tr>
                                                                <th class="text-right">{{ $taxName }} :</th>
                                                                <td class="text-right">
                                                                    {{ currency_format_with_sym($taxPrice) }}</td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td>
                                                        <hr />
                                                        <h5 class="text-primary m-r-10">{{ __('Total') }} :</h5>
                                                    </td>

                                                    <td>
                                                        <hr />
                                                        <h5 class="text-primary subTotal">
                                                            {{ currency_format_with_sym($invoice->getTotal()) }}</h5>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('From') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Address') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_address'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company City') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_city'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_zipcode'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Country') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_country'] }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Company Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $company_setting['company_telephone'] }}</span></dd>
                                    </dl>
                                </div>
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('Billing Address') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Address') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->billing_address }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing City') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->billing_city }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span>
                                        </dt>
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
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('Shipping Address') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Address') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $invoice->shipping_address }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping City') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $invoice->shipping_city }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $invoice->shipping_postalcode }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Country') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $invoice->shipping_country }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Shipping Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ !empty($invoice->contacts->name) ? $invoice->contacts->name : '--' }}</span>
                                        </dd>
                                    </dl>
                                </div>
                            </div>



                            <div class="row mt-4">
                                <div class="col-sm-12">
                                    <h3 class="px-2 py-2"><b>{{ __('Payment History') }}</b></h3>
                                    <div class="table-responsive mt-3">
                                        <table class="table invoice-detail-table">
                                            <thead>
                                                <tr class="thead-default">
                                                    <th>{{ __('Transaction ID') }}</th>
                                                    <th>{{ __('Payment Date') }}</th>
                                                    <th>{{ __('Payment Type') }}</th>
                                                    <th>{{ __('Receipt') }}</th>
                                                    <th>{{ __('Note') }}</th>
                                                    <th class="text-right">{{ __('Amount') }}</th>
                                                    @if (!empty($bank_transfer_payments))
                                                        <th>{{ __('Action') }}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $i=0; @endphp
                                                @if (!empty($retainer->payments) || !empty($bank_transfer_payments))
                                                    @foreach ($bank_transfer_payments as $bank_transfer_payment)
                                                        <tr>
                                                            <td>{{ $bank_transfer_payment->order_id }}</td>
                                                            <td>{{ company_datetime_formate($bank_transfer_payment->created_at) }}
                                                            </td>
                                                            <td>{{ 'Bank transfer' }}</td>
                                                            <td>
                                                                @if (!empty($bank_transfer_payment->attachment))
                                                                    <a href="{{ get_file($bank_transfer_payment->attachment) }}"
                                                                        target="_blank"> <i class="ti ti-file"></i></a>
                                                                @else
                                                                    --
                                                                @endif
                                                            </td>
                                                            <td>-</td>
                                                            <td class="text-right">
                                                                {{ currency_format_with_sym($bank_transfer_payment->price) }}
                                                            </td>
                                                            <td>
                                                                <div class="action-btn bg-primary ms-2">
                                                                    <a class="mx-3 btn btn-sm  align-items-center"
                                                                        data-url="{{ route('invoice.bank.request.edit', $bank_transfer_payment->id) }}"
                                                                        data-ajax-popup="true" data-size="md"
                                                                        data-bs-toggle="tooltip" title=""
                                                                        data-title="{{ __('Request Action') }}"
                                                                        data-bs-original-title="{{ __('Action') }}">
                                                                        <i class="ti ti-caret-right text-white"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @foreach ($invoice->payments as $payment)
                                                        <tr>
                                                            <td>{{ sprintf('%05d', $payment->transaction_id) }}</td>
                                                            <td>{{ company_date_formate($payment->date) }}</td>
                                                            <td>{{ $payment->payment_type }}</td>
                                                            <td>
                                                                @if ($payment->payment_type == 'STRIPE')
                                                                    <a href="{{ $payment->receipt }}" target="_blank">
                                                                        <i class="ti ti-file-invoice"></i>
                                                                    </a>
                                                                @elseif($payment->payment_type == 'Bank Transfer')
                                                                    <a href="{{ !empty($payment->receipt) ? (check_file($payment->receipt) ? get_file($payment->receipt) : '#!') : '#!' }}"
                                                                        target="_blank">
                                                                        <i class="ti ti-file"></i>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>{{ !empty($payment->notes) ? $payment->notes : '-' }}</td>
                                                            <td class="text-right">
                                                                {{ currency_format_with_sym($payment->amount) }}</td>
                                                            <td></td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    @include('layouts.nodatafound')
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-sm-2">
            <div class="card">
                <div class="card-footer py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0">
                            <div class="row align-items-center">
                                <dt class="col-sm-12"><span class="h6 text-sm mb-0">{{ __('Assigned User') }}</span>
                                </dt>
                                <dd class="col-sm-12"><span
                                        class="text-sm">{{ !empty($invoice->assign_user) ? $invoice->assign_user->name : '' }}</span>
                                </dd>

                                <dt class="col-sm-12"><span class="h6 text-sm mb-0">{{ __('Created') }}</span></dt>
                                <dd class="col-sm-12"><span
                                        class="text-sm">{{ company_date_formate($invoice->created_at) }}</span></dd>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- [ Invoice ] end -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on('change', 'select[name=item]', function() {
            var item_id = $(this).val();
            $.ajax({
                url: '{{ route('salesinvoice.items') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'item_id': item_id,
                },
                cache: false,
                success: function(data) {
                    var invoiceItems = JSON.parse(data);
                    $('.taxId').val('');
                    $('.tax').html('');
                    $('.price').val(invoiceItems.sale_price);
                    $('.quantity').val(1);
                    $('.discount').val(0);
                    var taxes = '';
                    var tax = [];
                    for (var i = 0; i < invoiceItems.taxes.length; i++) {
                        taxes += '<span class="badge bg-primary p-2 mx-1 rounded">' + invoiceItems
                            .taxes[i]
                            .name + ' ' + '(' + invoiceItems.taxes[i].rate + '%)' + '</span>';
                    }
                    $('.taxId').val(invoiceItems.tax_id);
                    $('.tax').html(taxes);
                }
            });
        });


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
@endpush
