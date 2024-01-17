@extends('layouts.main')
@section('page-title')
    {{ __('Sales Order') }}
@endsection
@section('title')
    {{ __('Sales Order') }} {{ '(' . $salesOrder->name . ')' }}
@endsection
@section('page-breadcrumb')
    {{ __('Sales Order') }},
    {{ __('Details') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('salesorder.pdf', \Crypt::encrypt($salesOrder->id)) }}" target="_blank"
            class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="{{ __('Print') }}">
            <span class="btn-inner--icon text-white"><i class="ti ti-printer"></i></span>
        </a>
        <a href="#" class="btn btn-sm btn-warning btn-icon cp_link"
            data-link="{{ route('pay.salesorder', \Illuminate\Support\Facades\Crypt::encrypt($salesOrder->id)) }}"
            data-bs-toggle="tooltip"
            data-title="{{ __('Click to copy SalesOrder link') }}"title="{{ __('copy link') }}"><span
                class="btn-inner--icon text-white"><i class="ti ti-file"></i></span></a>
        @can('salesorder edit')
            <a href="{{ route('salesorder.edit', $salesOrder->id) }}" class="btn btn-sm btn-info btn-icon"
                data-bs-toggle="tooltip" data-title="{{ __('Sales order Edit') }}" title="{{ __('Edit') }}"><i
                    class="ti ti-pencil"></i>
            </a>
        @endcan
        @if (module_is_active('ProductService'))
            <a href="#" data-size="md" data-url="{{ route('salesorder.salesorderitem', $salesOrder->id) }}"
                data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{ __('Create New SalesOrder') }}"
                title="{{ __('Create') }}" class="btn btn-sm btn-primary btn-icon">
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
                                        <h6 class="d-inline-block m-0 d-print-none">{{ __('SalesOrder ID') }}</h6>
                                        <span class="col-sm-8"><span
                                                class="text-sm">{{ Modules\Sales\Entities\SalesOrder::salesorderNumberFormat($salesOrder->salesorder_id) }}</span></span>
                                    </div>
                                    <div class="col-lg-6 col-md-8 mt-3">
                                        <h6 class="d-inline-block m-0 d-print-none">{{ __('SalesOrder Date') }}</h6>
                                        <span class="col-sm-8"><span
                                                class="text-sm">{{ company_date_formate($salesOrder->created_at) }}</span></span>
                                    </div>
                                    <h6 class="d-inline-block m-0 d-print-none mt-3">{{ __('SalesOrder ') }}</h6>
                                    @if ($salesOrder->status == 0)
                                        <span
                                            class="badge bg-primary p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesOrder->status]) }}</span>
                                    @elseif($salesOrder->status == 1)
                                        <span
                                            class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesOrder->status]) }}</span>
                                    @elseif($salesOrder->status == 2)
                                        <span
                                            class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesOrder->status]) }}</span>
                                    @elseif($salesOrder->status == 3)
                                        <span
                                            class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesOrder->status]) }}</span>
                                    @elseif($salesOrder->status == 4)
                                        <span
                                            class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesOrder->status]) }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-6 text-sm-end">

                                    <div>
                                        <div class="float-end mt-3">
                                            {!! DNS2D::getBarcodeHTML(
                                                route('pay.salesorder', \Illuminate\Support\Facades\Crypt::encrypt($salesOrder->id)),
                                                'QRCODE',
                                                2,
                                                2,
                                            ) !!}
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($customFields) && count($salesOrder->customField) > 0)
                                    @foreach ($customFields as $field)
                                        <div class="col text-md-end">
                                            <small>
                                                <strong>{{ $field->name }} :</strong><br>
                                                @if ($field->type == 'attachment')
                                                    <a href="{{ get_file($salesOrder->customField[$field->id]) }}"
                                                        target="_blank">
                                                        <img src=" {{ get_file($salesOrder->customField[$field->id]) }} "
                                                            class="wid-75 rounded me-3">
                                                    </a>
                                                @else
                                                    {{ !empty($salesOrder->customField[$field->id]) ? $salesOrder->customField[$field->id] : '-' }}
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
                                                @foreach ($salesOrder->items as $key => $salesOrderitem)
                                                    @php
                                                        $taxes = Modules\Sales\Entities\SalesUtility::tax($salesOrderitem->tax);
                                                        $totalQuantity += $salesOrderitem->quantity;
                                                        $totalRate += $salesOrderitem->price;
                                                        $totalDiscount += $salesOrderitem->discount;

                                                        if (!empty($taxes[0])) {
                                                            foreach ($taxes as $taxe) {
                                                                $taxDataPrice = Modules\Sales\Entities\SalesUtility::taxRate($taxe->rate, $salesOrderitem->price, $salesOrderitem->quantity, $salesOrderitem->discount);
                                                                if (array_key_exists($taxe->name, $taxesData)) {
                                                                    $taxesData[$taxe->name] = $taxesData[$taxe->name] + $taxDataPrice;
                                                                } else {
                                                                    $taxesData[$taxe->name] = $taxDataPrice;
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ !empty($salesOrderitem->items()) ? $salesOrderitem->items()->name : '' }}
                                                        </td>
                                                        <td>{{ $salesOrderitem->quantity }} </td>
                                                        <td>{{ currency_format_with_sym($salesOrderitem->price) }} </td>
                                                        <td>{{ currency_format_with_sym($salesOrderitem->discount) }} </td>
                                                        <td>
                                                            <div class="col">
                                                                @php
                                                                    $totalTaxPrice = 0;
                                                                    $data = 0;
                                                                    $taxPrice = 0;
                                                                @endphp
                                                                @if (module_is_active('ProductService'))
                                                                    @if (!empty($salesOrderitem->tax))
                                                                        @php $totalTaxRate = 0;@endphp
                                                                        @foreach ($salesOrderitem->tax($salesOrderitem->tax) as $tax)
                                                                            @php
                                                                                $taxPrice = Modules\Sales\Entities\SalesUtility::taxRate($tax->rate, $salesOrderitem->price, $salesOrderitem->quantity, $salesOrderitem->discount);
                                                                                $totalTaxPrice += $taxPrice;
                                                                                $data += $taxPrice;
                                                                            @endphp
                                                                            <a href="#!"
                                                                                class="d-block text-sm text-muted">{{ $tax->name . ' (' . $tax->rate . '%)' }}
                                                                                &nbsp;&nbsp;{{ currency_format_with_sym($taxPrice) }}</a>
                                                                        @endforeach
                                                                        @php
                                                                            array_push($TaxPrice_array, $data);
                                                                        @endphp
                                                                    @else
                                                                        <a href="#!"
                                                                            class="d-block text-sm text-muted">{{ __('No Tax') }}</a>
                                                                    @endif
                                                                @else
                                                                    <a href=""></a>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td style="white-space: break-spaces;">
                                                            {{ !empty($salesOrderitem->description) ? $salesOrderitem->description : '--' }}
                                                        </td>
                                                        @php
                                                            $tr_tex = array_key_exists($key, $TaxPrice_array) == true ? $TaxPrice_array[$key] : 0;
                                                        @endphp
                                                        <td class="text-right">
                                                            {{ currency_format_with_sym($salesOrderitem->price * $salesOrderitem->quantity - $salesOrderitem->discount + $tr_tex) }}
                                                        </td>
                                                        <td class="text-right">
                                                            @if (module_is_active('ProductService'))
                                                                @can('salesorder edit')
                                                                    <div class="action-btn bg-info ms-2">
                                                                        <a href="#"
                                                                            data-url="{{ route('salesorder.item.edit', $salesOrderitem->id) }}"
                                                                            data-ajax-popup="true"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"
                                                                            data-bs-toggle="tooltip"
                                                                            data-title="{{ __('Edit Item') }}"
                                                                            data-original-title="{{ __('Edit') }}"
                                                                            title="{{ __('Edit Item') }}"><i
                                                                                class="ti ti-pencil"></i></a>
                                                                    </div>
                                                                @endcan
                                                            @endif
                                                            @can('salesorder delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['salesorder.items.delete', $salesOrderitem->id]]) !!}
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
                                                            $totalQuantity += $salesOrderitem->quantity;
                                                            $totalRate += $salesOrderitem->price;
                                                            $totalDiscount += $salesOrderitem->discount;
                                                            $totalAmount += $salesOrderitem->price * $salesOrderitem->quantity;
                                                        @endphp
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <div class="invoice-total">
                                        <table class="table invoice-table ">
                                            <tbody>
                                                <tr>
                                                    <th>{{ 'Sub Total' }} :</th>
                                                    <td>{{ currency_format_with_sym($salesOrder->getSubTotal()) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ __('Discount') }} :</th>
                                                    <td>{{ currency_format_with_sym($salesOrder->getTotalDiscount()) }}
                                                    </td>
                                                </tr>
                                                @if (!empty($taxesData))
                                                    @foreach ($taxesData as $taxName => $taxPrice)
                                                        @if ($taxName != 'No Tax')
                                                            <tr>
                                                                <th>{{ $taxName }} :</th>
                                                                <td>{{ currency_format_with_sym($taxPrice) }}</td>
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
                                                            {{ currency_format_with_sym($salesOrder->getTotal()) }}</h5>
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
                                    </dl>
                                </div>
                                <div class="col-12 col-md-4">
                                    <h5>{{ __('Billing Address') }}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Address') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $salesOrder->billing_address }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing City') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesOrder->billing_city }}</span>
                                        </dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{ __('Zip Code') }}</span>
                                        </dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $salesOrder->billing_postalcode }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Country') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ $salesOrder->billing_country }}</span></dd>

                                        <dt class="col-sm-6"><span
                                                class="h6 text-sm mb-0">{{ __('Billing Contact') }}</span></dt>
                                        <dd class="col-sm-6"><span
                                                class="text-sm">{{ !empty($salesOrder->contacts->name) ? $salesOrder->contacts->name : '--' }}</span>
                                        </dd>
                                    </dl>
                                </div>
                                @if (company_setting('salesorder_shipping_display') == 'on')
                                    <div class="col-12 col-md-4">
                                        <h5>{{ __('Shipping Address') }}</h5>
                                        <dl class="row mt-4 align-items-center">
                                            <dt class="col-sm-6"><span
                                                    class="h6 text-sm mb-0">{{ __('Shipping Address') }}</span></dt>
                                            <dd class="col-sm-6"><span
                                                    class="text-sm">{{ $salesOrder->shipping_address }}</span></dd>

                                            <dt class="col-sm-6"><span
                                                    class="h6 text-sm mb-0">{{ __('Shipping City') }}</span></dt>
                                            <dd class="col-sm-6"><span
                                                    class="text-sm">{{ $salesOrder->shipping_city }}</span></dd>

                                            <dt class="col-sm-6"><span
                                                    class="h6 text-sm mb-0">{{ __('Zip Code') }}</span></dt>
                                            <dd class="col-sm-6"><span
                                                    class="text-sm">{{ $salesOrder->shipping_postalcode }}</span></dd>

                                            <dt class="col-sm-6"><span
                                                    class="h6 text-sm mb-0">{{ __('Shipping Country') }}</span></dt>
                                            <dd class="col-sm-6"><span
                                                    class="text-sm">{{ $salesOrder->shipping_country }}</span></dd>

                                            <dt class="col-sm-6"><span
                                                    class="h6 text-sm mb-0">{{ __('Shipping Contact') }}</span></dt>
                                            <dd class="col-sm-6"><span
                                                    class="text-sm">{{ !empty($salesOrder->contacts->name) ? $salesOrder->contacts->name : '--' }}</span>
                                            </dd>
                                        </dl>
                                    </div>
                                @endif
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
                                <dt class="col-sm-12"><span class="h6 text-sm mb-0">{{ __('Assigned User') }}</span></dt>
                                <dd class="col-sm-12"><span
                                        class="text-sm">{{ !empty($salesOrder->assign_user) ? $salesOrder->assign_user->name : '' }}</span>
                                </dd>

                                <dt class="col-sm-12"><span class="h6 text-sm mb-0">{{ __('Created') }}</span></dt>
                                <dd class="col-sm-12"><span
                                        class="text-sm">{{ company_date_formate($salesOrder->created_at) }}</span></dd>
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
                url: '{{ route('quote.items') }}',
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
                        taxes += '<span class="badge bg-primary p-2 rounded">' + invoiceItems.taxes[i]
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
            toastrs('success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>
@endpush
