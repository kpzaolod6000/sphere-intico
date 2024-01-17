@extends('sales::layouts.invoicepayheader')
@section('page-title')
    {{__('Sales Order')}} {{ '('. $salesorder->name .')' }}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">{{__('Sales Order')}} {{ '('. $salesorder->name .')' }}</h5>
    </div>
@endsection

@section('action-btn')
    <a href="{{route('salesorder.pdf',\Crypt::encrypt($salesorder->id))}}" target="_blank" class="btn btn-sm btn-primary btn-icon">
        <span class="btn-inner--icon text-white"><i class="fa fa-print"></i></span>
        <span class="btn-inner--text text-white">{{__('Print')}}</span>
    </a>

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
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Sales Order')}}</h6>

                                    @if($salesorder->status == 0)
                                        <span class="badge bg-primary p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                                    @elseif($salesorder->status == 1)
                                        <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                                    @elseif($salesorder->status == 2)
                                        <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                                    @elseif($salesorder->status == 3)
                                        <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                                    @elseif($salesorder->status == 4)
                                        <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-8">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Sales Order ID')}}</h6>
                                    <span class="col-sm-8"><span class="text-sm">{{ Modules\Sales\Entities\SalesOrder::salesorderNumberFormat($salesorder->salesorder_id,$salesorder->created_by,$salesorder->workspace) }}</span></span>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Assigned User : ')}}</h6>
                                    <span class="text-sm">{{ !empty($salesorder->assign_user)?$salesorder->assign_user->name:''}}</span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-lg-6 col-md-8">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Sales Order Date')}}</h6>
                                    <span class="col-sm-8"><span class="text-sm">{{company_date_formate($salesorder->date_quoted,$salesorder->created_by,$salesorder->workspace)}}</span></span>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Created : ')}}</h6>
                                   <span class="text-sm">{{company_date_formate($salesorder->created_at,$salesorder->created_by,$salesorder->workspace)}}</span>
                                </div>
                            </div>
                            @if(!empty($customFields) && count($salesorder->customField)>0)
                                @foreach($customFields as $field)
                                    <div class="col text-md-end">
                                        <small>
                                            <strong>{{$field->name}} :</strong><br>
                                            {{!empty($salesorder->customField[$field->id])?$salesorder->customField[$field->id]:'-'}}
                                            <br><br>
                                        </small>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <h5>{{__('From')}}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Company Address')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $company_setting['company_address'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Company City')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $company_setting['company_city'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Zip Code')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $company_setting['company_zipcode'] }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Company Country')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $company_setting['company_country'] }}</span></dd>
                                    </dl>
                                </div>
                                <div class="col-12 col-md-4">
                                    <h5>{{__('Billing Address')}}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Billing Address')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->billing_address }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Billing City')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->billing_city }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Zip Code') }}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->billing_postalcode }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Billing Country')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->billing_country }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Billing Contact')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ !empty($salesorder->contacts->name)?$salesorder->contacts->name:'--'}}</span></dd>
                                    </dl>
                                </div>
                                @if (company_setting('salesorder_shipping_display', $salesorder->created_by,$salesorder->workspace) == 'on')
                                <div class="col-12 col-md-4">
                                    <h5>{{__('Shipping Address')}}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Shipping Address')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->shipping_address }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Shipping City')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->shipping_city }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Zip Code')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->shipping_postalcode }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Shipping Country')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ $salesorder->shipping_country }}</span></dd>

                                        <dt class="col-sm-6"><span class="h6 text-sm mb-0">{{__('Shipping Contact')}}</span></dt>
                                        <dd class="col-sm-6"><span class="text-sm">{{ !empty($salesorder->contacts->name)?$salesorder->contacts->name:'--'}}</span></dd>
                                    </dl>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="py-2 px-2">{{__('Item List')}}</h5>
                                    <div class="table-responsive mt-4">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th>{{__('Item')}}</th>
                                                <th>{{__('Quantity')}}</th>
                                                <th>{{__('Price')}}</th>
                                                <th>{{__('Discount')}}</th>
                                                <th>{{__('Tax')}}</th>
                                                <th>{{__('Description')}}</th>
                                                <th class="text-end">{{__('Price')}}</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalAmount=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                            @endphp
                                            @foreach($salesorder->items as $salesorderitem)
                                                @php
                                                    $taxes=\Modules\Sales\Entities\SalesUtility::tax($salesorderitem->tax);
                                                    $totalQuantity+=$salesorderitem->quantity;
                                                    $totalRate+=$salesorderitem->price;
                                                    $totalDiscount+=$salesorderitem->discount;
                                                    if(!empty($taxes[0]))
                                                    {
                                                        foreach($taxes as $taxe)
                                                        {
                                                            $taxDataPrice=\Modules\Sales\Entities\SalesUtility::taxRate($taxe->rate,$salesorderitem->price,$salesorderitem->quantity,$salesorderitem->discount);
                                                            if (array_key_exists($taxe->name,$taxesData))
                                                            {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                            }
                                                            else
                                                            {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{!empty($salesorderitem->items())?$salesorderitem->items()->name:''}}</td>
                                                    <td>{{$salesorderitem->quantity}} </td>
                                                    <td>{{currency_format_with_sym($salesorderitem->price,$salesorder->created_by,$salesorder->workspace)}} </td>
                                                    <td>{{currency_format_with_sym($salesorderitem->discount,$salesorder->created_by,$salesorder->workspace)}} </td>
                                                    <td>
                                                        <div class="col">
                                                            @php
                                                                $taxPrice = 0;
                                                            @endphp
                                                            @if(module_is_active('ProductService'))
                                                                @if(!empty($salesorderitem->tax))
                                                                    @foreach($salesorderitem->tax($salesorderitem->tax) as $tax)
                                                                        @php
                                                                            $taxPrice=\Modules\Sales\Entities\SalesUtility::taxRate($tax->rate,$salesorderitem->price,$salesorderitem->quantity,$salesorderitem->discount);
                                                                            $totalTaxPrice+=$taxPrice;
                                                                        @endphp
                                                                        <a href="#!" class="d-block text-sm text-muted">{{$tax->name .' ('.$tax->rate .'%)'}} &nbsp;&nbsp;{{currency_format_with_sym($taxPrice,$salesorder->created_by,$salesorder->workspace)}}</a>
                                                                    @endforeach
                                                                @else
                                                                    <a href="#!" class="d-block text-sm text-muted">{{__('No Tax')}}</a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td style="white-space: break-spaces;">{{$salesorderitem->description}} </td>
                                                    <td class="text-end"> {{currency_format_with_sym(($salesorderitem->price*$salesorderitem->quantity)-$salesorderitem->discount+$taxPrice,$salesorder->created_by,$salesorder->workspace)}}</td>

                                                    @php
                                                        $totalQuantity+=$salesorderitem->quantity;
                                                        $totalRate+=$salesorderitem->price;
                                                        $totalDiscount+=$salesorderitem->discount;
                                                        $totalAmount+=($salesorderitem->price*$salesorderitem->quantity);
                                                    @endphp
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                                <td></td>
                                                <td class="text-end"><strong>{{__('Sub Total')}}</strong></td>
                                                <td class="text-end subTotal">{{currency_format_with_sym($salesorder->getSubTotal(),$salesorder->created_by,$salesorder->workspace)}}</td>

                                            </tr>

                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                                <td></td>
                                                <td class="text-end"><strong>{{__('Discount')}}</strong></td>
                                                <td class="text-end subTotal">{{currency_format_with_sym($salesorder->getTotalDiscount(),$salesorder->created_by,$salesorder->workspace)}}</td>

                                            </tr>
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    @if($taxName != 'No Tax')
                                                        <tr>
                                                            <td colspan="4"></td>
                                                            <td></td>
                                                            <td class="text-end"><b>{{$taxName}}</b></td>
                                                            <td class="text-end">{{ currency_format_with_sym($taxPrice,$salesorder->created_by,$salesorder->workspace) }}</td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                                <td></td>
                                                <td class="text-end"><strong>{{__('Total')}}</strong></td>
                                                <td class="text-end subTotal">{{currency_format_with_sym( $salesorder->getTotal(),$salesorder->created_by,$salesorder->workspace)}}</td>

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
                                                        <span class="h6 text-muted d-inline-block mr-3 mb-0">{{__('Total value')}}:</span>
                                                        <span class="h4 mb-0">{{currency_format_with_sym($salesorder->getTotal(),$salesorder->created_by,$salesorder->workspace)}}</span>
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
    </div>

@endsection
