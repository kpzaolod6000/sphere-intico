@extends('layouts.main')
@section('page-title')
    {{ __('Ledger Summary') }}
@endsection



@section('page-breadcrumb')
    {{__('Ledger Summary')}}
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endpush

@section('page-action')
    <div class="float-end">

        <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()"data-bs-toggle="tooltip"
           title="{{ __('Download') }}" data-original-title="{{ __('Download') }}">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.ledger'], 'method' => 'GET', 'id' => 'report_ledger']) }}

                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', $filter['startDateRange'], ['class' => 'month-btn form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', $filter['endDateRange'], ['class' => 'month-btn form-control']) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                                            {{ Form::select('account', $accounts, isset($_GET['account']) ? $_GET['account'] : '', ['class' => 'form-control select']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">
                                        <a href="#" class="btn btn-sm btn-primary"
                                           onclick="document.getElementById('report_ledger').submit(); return false;"
                                           data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                           data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('report.ledger') }}" class="btn btn-sm btn-danger "
                                           data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                           data-original-title="{{ __('Reset') }}">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></span>
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
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th> {{ __('Account Name') }}</th>
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Transaction Type') }}</th>
                                    <th> {{ __('Transaction Date') }}</th>
                                    <th> {{ __('Debit') }}</th>
                                    <th> {{ __('Credit') }}</th>
                                    <th> {{ __('Balance') }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @php
                                    $balance = 0;
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                    $accountArrays = [];

                                    foreach ($chart_accounts as $key => $account) {

                                        $chartDatas = \Modules\Account\Entities\AccountUtility::getAccountData($account->id, $filter['startDateRange'], $filter['endDateRange']);
                                        $a = [0 => ['account' => $account->id]];
                                        $chartDatas = array_merge($chartDatas, $a);
                                        $accountArrays[] = $chartDatas;
                                    }
                                @endphp

                                @foreach ($accountArrays as $account)

                                    @foreach ($account[0] as $a)
                                        @php $accountName = \Modules\Account\Entities\ChartOfAccount::find($a); @endphp

                                        @foreach ($account['invoice'] as $invoiceData)
                                            @if ($account['invoice'] != [])
                                                <tr>
                                                    <td>{{ $accountName->name }}</td>
                                                    @php
                                                        $invoice = \App\Models\Invoice::where('id', $invoiceData->invoice_id)->first();
                                                    @endphp
                                                    <td>{{ !empty($invoice->customer) ? $invoice->customer->name : '-' }}
                                                    </td>
                                                    <td>{{ \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}
                                                    </td>
                                                    <td>{{ $invoiceData->created_at->format('d-m-Y') }}</td>
                                                    <td>-</td>

                                                    @php
                                                        $total = $invoiceData->price * $invoiceData->quantity;
                                                        $balance += $total;
                                                        $totalCredit += $total;
                                                    @endphp
                                                    <td>{{ currency_format_with_sym($total) }}</td>
                                                    <td>{{ currency_format_with_sym($balance) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        @foreach ($account['invoicepayment'] as $invoicePaymentData)

                                            <tr>
                                                <td>{{ $accountName->name }}</td>
                                                @php
                                                    $invoice = \App\Models\Invoice::where('id', $invoicePaymentData->invoice_id)->first();
                                                @endphp
                                                <td>{{ !empty($invoice->customer) ? $invoice->customer->name : '-' }}
                                                </td>
                                                <td>{{ \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}
                                                    {{ __('Manually Payment') }}</td>
                                                <td>{{ $invoicePaymentData->created_at->format('d-m-Y') }}</td>
                                                <td>{{ currency_format_with_sym($invoicePaymentData->amount) }}</td>
                                                <td>-</td>
                                                @php
                                                    $balance += $invoicePaymentData->amount;
                                                    $totalCredit += $invoicePaymentData->amount;
                                                @endphp
                                                <td>{{ currency_format_with_sym($balance) }}</td>


                                            </tr>
                                        @endforeach

                                        @foreach ($account['revenue'] as $revenueData)
                                            <tr>
                                                <td>{{ $accountName->name }}</td>
                                                <td>{{ !empty($revenueData->customer) ? $revenueData->customer->name : '-' }}
                                                </td>
                                                <td>{{ __('Revenue') }}</td>
                                                <td>{{ $revenueData->created_at->format('d-m-Y') }}</td>
                                                <td>-</td>
                                                <td>{{ currency_format_with_sym($revenueData->amount) }}</td>
                                                @php
                                                    $balance += $revenueData->amount;
                                                    $totalCredit += $revenueData->amount;
                                                @endphp
                                                <td>{{ currency_format_with_sym($balance) }}</td>
                                            </tr>
                                        @endforeach

                                        @foreach ($account['bill'] as $billProduct)
                                            <tr>
                                                <td>{{ $accountName->name }}</td>
                                                @php
                                                    $bill = Modules\Account\Entities\Bill::find($billProduct->bill_id);
                                                    $vendor = Modules\Account\Entities\Vender::find(!empty($bill) ? $bill->vendor_id : '');
                                                @endphp
                                                <td>{{ !empty($vendor) ? $vendor->name : '-' }}</td>
                                                <td>{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</td>
                                                <td>{{ $billProduct->created_at->format('d-m-Y') }}</td>

                                                @php
                                                    $total = $billProduct->price * $billProduct->quantity;
                                                    $balance -= $total;
                                                    $totalCredit -= $total;
                                                @endphp
                                                <td>{{ currency_format_with_sym($total) }}</td>
                                                <td>-</td>
                                                <td>{{ currency_format_with_sym($balance) }}</td>
                                            </tr>
                                        @endforeach

                                        @foreach ($account['billdata'] as $billData)
                                            @php
                                                $bill = Modules\Account\Entities\Bill::find($billData->ref_id);
                                                $vendor = Modules\Account\Entities\Vender::find(!empty($bill) ? $bill->vendor_id : '');
                                            @endphp
                                            <tr>
                                                <td>{{ $accountName->name }}</td>
                                                <td>{{ !empty($vendor) ? $vendor->name : '-' }}</td>
                                                @if (!empty($bill->bill_id))
                                                    <td>{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif

                                                <td>{{ $billData->created_at->format('d-m-Y') }}</td>
                                                <td>{{ currency_format_with_sym($billData->price) }}</td>
                                                <td>-</td>
                                                @php
                                                    $balance -= $billData->price;
                                                    $totalDebit -= $billData->price;
                                                @endphp
                                                <td>{{ currency_format_with_sym($balance) }}</td>
                                            </tr>
                                        @endforeach

                                        @foreach ($account['billpayment'] as $billPaymentData)
                                            @if($account['billpayment'] != [])
                                                @php
                                                    $bill = Modules\Account\Entities\BillPayment::where('bill_id', $billPaymentData->bill_id)->first();
                                                    $billId = Modules\Account\Entities\Bill::find($billPaymentData->bill_id);
                                                    $vendor = Modules\Account\Entities\Vender::find($billId->vendor_id);
                                                @endphp
                                                <tr>
                                                    <td>{{ $accountName->name }}</td>
                                                    <td>{{ !empty($vendor) ? $vendor->name : '-' }}</td>
                                                    <td>{{ Modules\Account\Entities\Bill::billNumberFormat($billId->bill_id) }}{{ __(' Manually Payment') }}
                                                    </td>
                                                    <td>{{ $billPaymentData->created_at->format('d-m-Y') }}</td>
                                                    <td>{{ currency_format_with_sym($billPaymentData->amount) }}</td>
                                                    <td>-</td>
                                                    @php
                                                        $balance -= $billPaymentData->amount;
                                                    @endphp
                                                    <td>{{ currency_format_with_sym($balance) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach



                                        @foreach ($account['payment'] as $paymentData)
                                            @php
                                                $vendor = Modules\Account\Entities\Vender::find($paymentData->vendor_id);
                                            @endphp
                                            <tr>
                                                <td>{{ $accountName->name }}</td>
                                                <td>{{ !empty($vendor) ? $vendor->name : '-' }}</td>
                                                <td>{{ __('Payment') }}</td>
                                                <td>{{ $paymentData->created_at->format('d-m-Y') }}</td>
                                                <td>{{ currency_format_with_sym($paymentData->amount) }}</td>
                                                <td>-</td>
                                                @php
                                                    $balance -= $paymentData->amount;
                                                    $totalDebit -= $paymentData->amount;
                                                @endphp
                                                <td>{{ currency_format_with_sym($balance) }}</td>
                                            </tr>
                                        @endforeach

                                        @php
                                            $debit = 0;
                                            $credit = 0;
                                        @endphp


                                        @foreach ($account['journalItem'] as $journalItemData)
                                            <tr>
                                                <td>{{ $accountName->name }}</td>
                                                <td>{{ '-' }}</td>
                                                <td>{{ Modules\DoubleEntry\Entities\JournalEntry::journalNumberFormat($journalItemData->journal_id) }}
                                                </td>
                                                <td>{{ $journalItemData->created_at->format('d-m-Y') }}</td>
                                                @if($journalItemData->debit == 0)
                                                    <td>{{ '-' }}</td>
                                                @else
                                                    <td>{{ currency_format_with_sym($journalItemData->debit) }}</td>
                                                @endif
                                                @if($journalItemData->credit == 0)
                                                    <td>{{ '-' }}</td>
                                                @else
                                                    <td>{{ currency_format_with_sym($journalItemData->credit) }}</td>
                                                @endif
                                                <td>
                                                    @if ($journalItemData->debit)
                                                        @php $balance -= $journalItemData->debit @endphp
                                                    @else
                                                        @php $balance += $journalItemData->credit @endphp
                                                    @endif
                                                    {{ currency_format_with_sym($balance) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
