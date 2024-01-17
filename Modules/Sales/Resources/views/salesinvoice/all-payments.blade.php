@extends('layouts.admin')
@section('page-title')
    {{__('Manage Payment')}}
@endsection
@section('title')
        <div class="page-header-title">
            <h4 class="m-b-10">{{__('Payment')}}</h4>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
            <li class="breadcrumb-item " aria-current="page">{{__('Payment')}}</li>
        </ul>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable" id="datatable">
                        <thead>
                            <tr>
                                <th> {{__('Transaction ID')}}</th>
                                    <th> {{__('Invoice')}}</th>
                                    <th> {{__('Payment Date')}}</th>
                                    <th> {{__('Payment Type')}}</th>
                                    <th> {{__('Note')}}</th>
                                    <th> {{__('Amount')}}</th>
                                    @if(Gate::check('Show Invoice'))
                                        <th>{{__('Action')}}</th>
                                    @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{sprintf("%05d", $payment->transaction_id)}}</td>
                                <td>{{ \Auth::user()->invoiceNumberFormat($payment->invoice->invoice_id) }}</td>
                                <td>{{ Auth::user()->dateFormat($payment->date) }}</td>
                                <td>{{$payment->payment_type}}</td>
                                <td>{{!empty($payment->notes) ? $payment->notes : '-'}}</td>
                                <td>{{Auth::user()->priceFormat($payment->amount)}}</td>
                                @if(Gate::check('Show Invoice'))

                                    <td class="Action">
                                        <div class="action-btn bg-warning ms-2">
                                            <a href="{{ route('invoice.show',$payment->invoice->id) }}"
                                                data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                class="mx-3 btn btn-sm align-items-center text-white "
                                                data-title="{{ __('Invoice Details') }}">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
