@extends('sales::layouts.invoicepayheader')
@section('page-title')
    {{__('Quote')}} {{ '('. $quote->name .')' }}
@endsection

@section('action-btn')
<a href="{{route('quote.pdf',\Crypt::encrypt($quote->id))}}" target="_blank" class="btn btn-sm btn-primary btn-icon ">
    <span class="btn-inner--icon text-white"><i class="fa fa-print"></i></span>
    <span class="btn-inner--text text-white">{{__('Print')}}</span>
</a>
@endsection
@section('content')
    <div class="row">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <dl class="row">
                        <div class="col-12">
                            <div class="row align-items-center mb-2">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                </div>
                                <div class="col-sm-6 text-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Quote')}}</h6>

                                    @if($quote->status == 0)
                                        <span class="badge bg-primary p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                    @elseif($quote->status == 1)
                                        <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                    @elseif($quote->status == 2)
                                        <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                    @elseif($quote->status == 3)
                                        <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                    @elseif($quote->status == 4)
                                        <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-8">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Quote ID')}}</h6>
                                    <span class="col-sm-8"><span class="text-sm">{{ Modules\Sales\Entities\Quote::quoteNumberFormat($quote->quote_id,$quote->created_by,$quote->workspace) }}</span></span>
                                </div>

                                <div class="col-lg-6 text-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Assigned User :')}}</h6>
                                    <span class="text-sm">{{ !empty($quote->assign_user)?$quote->assign_user->name:''}}</span>
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-lg-6 col-md-8">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Quote Date')}}</h6>
                                    <span class="col-sm-8"><span class="text-sm">{{company_date_formate($quote->date_quoted,$quote->created_by,$quote->workspace)}}</span></span>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <h6 class="d-inline-block m-0 d-print-none">{{__('Created :')}}</h6>
                                    <span class="text-sm">{{company_date_formate($quote->created_at,$quote->created_by,$quote->workspace)}}</span>
                                </div>
                            </div>
                            @if(!empty($customFields) && count($quote->customField)>0)
                                @foreach($customFields as $field)
                                    <div class="col text-md-end">
                                        <small>
                                            <strong>{{$field->name}} :</strong><br>
                                            {{!empty($quote->customField[$field->id])?$quote->customField[$field->id]:'-'}}
                                            <br><br>
                                        </small>
                                    </div>
                                @endforeach
                            @endif
                            <div class="row mb-3">
                                <div class="col-12 col-md-4">
                                    <h5>{{__('From')}}</h5>
                                    <div class="row mt-4 align-items-center">
                                        <div class="col-sm-4 h6 text-m">{{__('Company Address')}}</div>
                                        <div class="col-sm-8 text-m">{{ $company_setting['company_address'] }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Company City')}}</div>
                                        <div class="col-sm-8 text-m">{{ $company_setting['company_city'] }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Zip Code')}}</div>
                                        <div class="col-sm-8 text-m">{{ $company_setting['company_zipcode'] }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Company Country')}}</div>
                                        <div class="col-sm-8 text-m">{{ $company_setting['company_country'] }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Company Contact')}}</div>
                                        <div class="col-sm-8 text-m">{{ $company_setting['company_telephone']}}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <h5>{{__('Billing Address')}}</h5>
                                    <div class="row mt-4 align-items-center">
                                        <div class="col-sm-4 h6 text-m">{{__('Billing Address')}}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->billing_address }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Billing City')}}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->billing_city }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Zip Code') }}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->billing_postalcode }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Billing Country')}}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->billing_country }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Billing Contact')}}</div>
                                        <div class="col-sm-8 text-m">{{ !empty($quote->contacts->name)?$quote->contacts->name:'--'}}</div>
                                    </div>
                                </div>
                                @if (company_setting('quote_shipping_display', $quote->created_by,$quote->workspace) == 'on')
                                <div class="col-12 col-md-4">
                                    <h5>{{__('Shipping Address')}}</h5>
                                    <dl class="row mt-4 align-items-center">
                                        <div class="col-sm-4 h6 text-m">{{__('Shipping Address')}}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->shipping_address }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Shipping City')}}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->shipping_city }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Zip Code')}}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->shipping_postalcode }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Shipping Country')}}</div>
                                        <div class="col-sm-8 text-m">{{ $quote->shipping_country }}</div>

                                        <div class="col-sm-4 h6 text-m">{{__('Shipping Contact')}}</div>
                                        <div class="col-sm-8 text-m">{{ !empty($quote->contacts->name)?$quote->contacts->name:'--'}}</div>
                                    </dl>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5 class="px-2 py-2">{{__('Item List')}}</h5>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                <th>{{__('Item')}}</th>
                                                <th>{{__('Quantity')}}</th>
                                                <th>{{__('Price')}}</th>
                                                <th>{{__('Discount')}}</th>
                                                <th>{{__('Tax')}}</th>
                                                <th>{{__('Description')}}</th>
                                                <th>{{__('Price')}}</th>

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
                                            @foreach($quote->items as $quoteitem)
                                                @php
                                                    $taxes=\Modules\Sales\Entities\SalesUtility::tax($quoteitem->tax);
                                                    $totalQuantity+=$quoteitem->quantity;
                                                    $totalRate+=$quoteitem->price;
                                                    $totalDiscount+=$quoteitem->discount;
                                                    if(!empty($taxes[0]))
                                                    {
                                                        foreach($taxes as $taxe)
                                                        {
                                                            $taxDataPrice=\Modules\Sales\Entities\SalesUtility::taxRate($taxe->rate,$quoteitem->price,$quoteitem->quantity,$quoteitem->discount);
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
                                                    <td>{{!empty($quoteitem->items())?$quoteitem->items()->name:''}} </td>
                                                    <td>{{$quoteitem->quantity}} </td>
                                                    <td>{{currency_format_with_sym($quoteitem->price,$quote->created_by,$quote->workspace)}} </td>
                                                    <td>{{currency_format_with_sym($quoteitem->discount,$quote->created_by,$quote->workspace)}} </td>
                                                    <td>
                                                        <div class="col">
                                                            @php
                                                                $taxPrice = 0;
                                                            @endphp
                                                            @if(module_is_active('ProductService'))
                                                                @if(!empty($quoteitem->tax))
                                                                    @foreach($quoteitem->tax($quoteitem->tax) as $tax)
                                                                        @php
                                                                            $taxPrice=\Modules\Sales\Entities\SalesUtility::taxRate($tax->rate,$quoteitem->price,$quoteitem->quantity,$quoteitem->discount);
                                                                            $totalTaxPrice+=$taxPrice;
                                                                        @endphp
                                                                        <a href="#!" class="d-block text-sm text-muted">{{$tax->name .' ('.$tax->rate .'%)'}} &nbsp;&nbsp;{{currency_format_with_sym($taxPrice,$quote->created_by,$quote->workspace)}}</a>
                                                                    @endforeach
                                                                @else
                                                                    <a href="#!" class="d-block text-sm text-muted">{{__('No Tax')}}</a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td style="white-space: break-spaces;">{{$quoteitem->description}} </td>
                                                    <td> {{currency_format_with_sym(($quoteitem->price*$quoteitem->quantity)-$quoteitem->discount+$taxPrice,$quote->created_by,$quote->workspace)}}</td>

                                                    @php
                                                        $totalQuantity+=$quoteitem->quantity;
                                                        $totalRate+=$quoteitem->price;
                                                        $totalDiscount+=$quoteitem->discount;
                                                        $totalAmount+=($quoteitem->price*$quoteitem->quantity);
                                                    @endphp
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                                <td></td>
                                                <td><strong>{{__('Sub Total')}}</strong></td>
                                                <td class="text- subTotal">{{currency_format_with_sym($quote->getSubTotal(),$quote->created_by,$quote->workspace)}}</td>

                                            </tr>

                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                                <td></td>
                                                <td><strong>{{__('Discount')}}</strong></td>
                                                <td class="text- subTotal">{{currency_format_with_sym($quote->getTotalDiscount(),$quote->created_by,$quote->workspace)}}</td>

                                            </tr>
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    @if($taxName != 'No Tax')
                                                        <tr>
                                                            <td colspan="4"></td>
                                                            <td></td>
                                                            <td><b>{{$taxName}}</b></td>
                                                            <td>{{ currency_format_with_sym($taxPrice,$quote->created_by,$quote->workspace) }}</td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                                <td></td>
                                                <td><strong>{{__('Total')}}</strong></td>
                                                <td class="text- subTotal">{{currency_format_with_sym( $quote->getTotal(),$quote->created_by,$quote->workspace)}}</td>

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
                                                        <span class="h4 mb-0">{{currency_format_with_sym($quote->getTotal(),$quote->created_by,$quote->workspace)}}</span>
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
