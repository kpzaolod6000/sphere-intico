@extends('layouts.main')
@section('page-title')
    {{__('Sales Orders Edit')}}
@endsection
@section('title')
    {{__('Edit Sales Order')}} {{ '('. $salesOrder->name .')' }}
@endsection
@section('page-action')
    <div class="btn-group" role="group">
        @if(!empty($previous))
        <div class="action-btn ms-2">
            <a href="{{ route('salesorder.edit',$previous) }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" title="{{__('Previous')}}">
                <i class="ti ti-chevron-left"></i>
            </a>
        </div>
        @else
        <div class="action-btn ms-2">
            <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip" title="{{__('Previous')}}">
                <i class="ti ti-chevron-left"></i>
            </a>
        </div>
        @endif
        @if(!empty($next))
        <div class="action-btn  ms-2">
            <a href="{{ route('salesorder.edit',$next) }}" class="btn btn-sm btn-primary btn-icon m-1" data-bs-toggle="tooltip" title="{{__('Next')}}">
                <i class="ti ti-chevron-right"></i>
            </a>
        </div>
        @else
        <div class="action-btn  ms-2">
            <a href="#" class="btn btn-sm btn-primary btn-icon m-1 disabled" data-bs-toggle="tooltip" title="{{__('Next')}}">
                <i class="ti ti-chevron-right"></i>
            </a>
        </div>
        @endif
    </div>
@endsection
@section('page-breadcrumb')
    {{__('Sales Order')}},
    {{__('Edit')}}
@endsection
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            <a href="#useradd-1" class="list-group-item list-group-item-action border-0">{{ __('Overview') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                            <a href="#useradd-2" class="list-group-item list-group-item-action border-0">{{__('Sales Invoice')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="card">
                        {{Form::model($salesOrder,array('route' => array('salesorder.update', $salesOrder->id), 'method' => 'PUT')) }}
                        <div class="card-header">
                            <div class="float-end">
                                @if (module_is_active('AIAssistant'))
                                    @include('aiassistant::ai.generate_ai_btn',['template_module' => 'salesorder','module'=>'Sales'])
                                @endif
                            </div>
                            <h5>{{ __('Overview') }}</h5>
                            <small class="text-muted">{{__('Edit About Your sales orders Information')}}</small>
                        </div>

                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                                            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                                            @error('name')
                                            <span class="invalid-name" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('quote',__('Quote'),['class'=>'form-label']) }}
                                            {!! Form::select('quote', $quote, null,array('class' => 'form-control')) !!}
                                            @error('quote')
                                            <span class="invalid-quote" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('opportunity',__('Opportunity'),['class'=>'form-label']) }}
                                            {!! Form::select('opportunity', $opportunity, null,array('class' => 'form-control')) !!}
                                            @error('opportunity')
                                            <span class="invalid-opportunity" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('status',__('Status'),['class'=>'form-label']) }}
                                            <select name="status" id="status" class="form-control"  required>
                                                @foreach($status as $k => $v)
                                                    <option value="{{$k}}" {{ ($salesOrder->status == $k) ? 'selected' : '' }}>{{__($v)}}</option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                            <span class="invalid-status" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('account',__('Account'),['class'=>'form-label']) }}
                                            {!! Form::select('account', $account, null,array('class' => 'form-control')) !!}
                                            @error('account')
                                            <span class="invalid-account" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('date_salesorder',__('Date SalesOrder'),['class'=>'form-label']) }}
                                            {{Form::date('date_quoted',null,array('class'=>'form-control','placeholder'=>__('Enter Date Quoted'),'required'=>'required'))}}
                                            @error('date_quoted')
                                            <span class="invalid-date_quoted" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('quote_number',__('Quote Number'),['class'=>'form-label']) }}
                                            {{Form::number('quote_number',null,array('class'=>'form-control','placeholder'=>__('Enter Quote Number'),'required'=>'required'))}}
                                            @error('quote_number')
                                            <span class="invalid-quote_number" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('billing_contact',__('Billing Contact'),['class'=>'form-label']) }}
                                            {!! Form::select('billing_contact', $billing_contact, null,array('class' => 'form-control')) !!}
                                            @error('billing_contact')
                                            <span class="invalid-billing_contact" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('billing_address',__('Billing Address'),['class'=>'form-label']) }}
                                            {{Form::text('billing_address',null,array('class'=>'form-control','placeholder'=>__('Enter Billing Address'),'required'=>'required'))}}
                                            @error('billing_address')
                                            <span class="invalid-billing_address" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('shipping_address',__('Shipping Address'),['class'=>'form-label']) }}
                                            {{Form::text('shipping_address',null,array('class'=>'form-control','placeholder'=>__('Enter Shipping Address'),'required'=>'required'))}}
                                            @error('shipping_address')
                                            <span class="invalid-shipping_address" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('billing_city',null,array('class'=>'form-control','placeholder'=>__('Enter Billing City'),'required'=>'required'))}}
                                            @error('billing_city')
                                            <span class="invalid-billing_city" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('billing_state',null,array('class'=>'form-control','placeholder'=>__('Enter Billing State'),'required'=>'required'))}}
                                            @error('billing_state')
                                            <span class="invalid-billing_state" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('shipping_city',null,array('class'=>'form-control','placeholder'=>__('Enter Shipping City'),'required'=>'required'))}}
                                            @error('shipping_city')
                                            <span class="invalid-shipping_city" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('shipping_state',null,array('class'=>'form-control','placeholder'=>__('Enter Shipping State'),'required'=>'required'))}}
                                            @error('shipping_state')
                                            <span class="invalid-shipping_state" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('billing_country',null,array('class'=>'form-control','placeholder'=>__('Enter Billing country'),'required'=>'required'))}}
                                            @error('billing_country')
                                            <span class="invalid-billing_country" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('billing_postalcode',null,array('class'=>'form-control','placeholder'=>__('Enter Billing Postal Code'),'required'=>'required'))}}
                                            @error('billing_postalcode')
                                            <span class="invalid-billing_postalcode" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('shipping_country',null,array('class'=>'form-control','placeholder'=>__('Enter Shipping Country'),'required'=>'required'))}}
                                            @error('shipping_country')
                                            <span class="invalid-shipping_country" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            {{Form::text('shipping_postalcode',null,array('class'=>'form-control','placeholder'=>__('Enter Shipping Postal Code'),'required'=>'required'))}}
                                            @error('shipping_postalcode')
                                            <span class="invalid-shipping_postalcode" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('shipping_contact',__('Shipping Contact'),['class'=>'form-label']) }}
                                            {!! Form::select('shipping_contact', $billing_contact, null,array('class' => 'form-control')) !!}
                                            @error('shipping_contact')
                                            <span class="invalid-shipping_contact" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('tax',__('Tax'),['class'=>'form-label']) }}
                                            {{ Form::select('tax[]', $tax,explode(',',$salesOrder->tax), array('class' => 'form-control choices','id'=>'choices-multiple1','multiple'=>'','required'=>'required')) }}
                                            @error('tax')
                                                <span class="invalid-tax" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <p class="text-danger d-none" id="tax_validation">{{__('Tax filed is required.')}}</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            {{Form::label('shipping_provider',__('Shipping Provider'),['class'=>'form-label']) }}
                                            {!! Form::select('shipping_provider', $shipping_provider, null,array('class' => 'form-control','required'=>'required')) !!}
                                            @error('shipping_provider')
                                            <span class="invalid-shipping_provider" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                        {{Form::label('user',__('Assigned User'),['class'=>'form-label']) }}
                                        {!! Form::select('user', $user, $salesOrder->user_id,array('class' => 'form-control')) !!}
                                        @error('user')
                                        <span class="invalid-user" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            {{Form::label('description',__('Description'),['class'=>'form-label']) }}
                                            {{Form::textarea('description',null,array('class'=>'form-control','rows'=>2,'placeholder'=>__('Enter Name')))}}
                                        </div>
                                    </div>
                                    @if(module_is_active('CustomField') && !$customFields->isEmpty())
                                        <div class="col-md-12">
                                            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                                @include('customfield::formBuilder',['fildedata' => $salesOrder->customField])
                                            </div>
                                        </div>
                                    @endif
                                    <div class="text-end">
                                        {{Form::submit(__('Update'),array('class'=>'btn-submit btn btn-primary','id'=>'submit'))}}
                                    </div>


                                </div>
                            </form>
                        </div>
                        {{Form::close()}}
                    </div>

                    <div id="useradd-2" class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Sales Invoice') }}</h5>
                                <small class="text-muted">{{__('Assigned invoice for this sales orders')}}</small>
                                </div>
                                <div class="col">
                                    <div class="float-end">
                                        <a data-size="lg" data-url="{{ route('salesinvoice.create',['salesorder',$salesOrder->id]) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Invoice')}}" class="btn btn-sm btn-primary btn-icon-only ">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table datatable" id="datatable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
                                            <th scope="col" class="sort" data-sort="budget">{{__('Account')}}</th>
                                            <th scope="col" class="sort" data-sort="status">{{__('Status')}}</th>
                                            <th scope="col" class="sort" data-sort="completion">{{__('Created At')}}</th>
                                            <th scope="col" class="sort" data-sort="completion">{{__('Amount')}}</th>
                                            <th scope="col" class="text-end">{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $invoice)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('salesinvoice.show',$invoice->id) }}" class="action-item text-primary" data-title="{{__('Sales Invoice Details')}}">
                                                        {{ $invoice->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{!empty( $invoice->accounts->name )? $invoice->accounts->name:'-'}}
                                                </td>
                                                <td>
                                                    @if($invoice->status == 0)
                                                        <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 1)
                                                        <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 2)
                                                        <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 3)
                                                        <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 4)
                                                        <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 5)
                                                        <span class="badge bg-danger p-2 px-3 roundedr">{{ __(Modules\Sales\Entities\SalesInvoice::$status[$invoice->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="budget">{{company_date_formate($invoice->created_at)}}</span>
                                                </td>
                                                <td>
                                                    <span class="budget">{{currency_format_with_sym($invoice->amount)}}</span>
                                                </td>
                                                <td class="text-end">
                                                    @can('salesinvoice show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ route('salesinvoice.show',$invoice->id) }}" data-bs-toggle="tooltip" data-original-title="{{__('Details')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center text-white" title="{{__('Invoice Details')}}">
                                                                <i class="ti ti-eye"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('salesinvoice edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('salesinvoice.edit',$invoice->id) }}" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center text-white" title="{{__('Edit Invoice')}}"><i class="ti ti-pencil"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('salesinvoice delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['salesinvoice.destroy', $invoice->id]]) !!}
                                                                <a href="#!" class="mx-3 btn btn-sm   align-items-center text-white show_confirm" data-bs-toggle="tooltip" title='Delete'>
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>


@endsection
@push('scripts')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })

        $(document).on('change', 'select[name=opportunity]', function () {

            var opportunities = $(this).val();
            getaccount(opportunities);
        });

        function getaccount(opportunities_id) {
            $.ajax({
                url: '{{route('quote.getaccount')}}',
                type: 'POST',
                data: {
                    "opportunities_id": opportunities_id, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#amount').val(data.opportunitie.amount);
                    $('#name').val(data.opportunitie.name);
                    $('#account_name').val(data.account.name);
                    $('#account_id').val(data.account.id);
                    $('#billing_address').val(data.account.billing_address);
                    $('#shipping_address').val(data.account.shipping_address);
                    $('#billing_city').val(data.account.billing_city);
                    $('#billing_state').val(data.account.billing_state);
                    $('#shipping_city').val(data.account.shipping_city);
                    $('#shipping_state').val(data.account.shipping_state);
                    $('#billing_country').val(data.account.billing_country);
                    $('#billing_postalcode').val(data.account.billing_postalcode);
                    $('#shipping_country').val(data.account.shipping_country);
                    $('#shipping_postalcode').val(data.account.shipping_postalcode);

                }
            });
        }

    </script>
    <script>
        $(function(){
            $("#submit").click(function() {
                var tax =  $("#choices-multiple1 option:selected").length;
                if(tax == 0){
                $('#tax_validation').removeClass('d-none')
                    return false;
                }else{
                $('#tax_validation').addClass('d-none')
                }
            });
        });
    </script>
@endpush
