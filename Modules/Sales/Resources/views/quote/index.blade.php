@extends('layouts.main')
@section('page-title')
    {{ __('Manage Quote') }}
@endsection
@section('title')
    {{ __('Quote') }}
@endsection
@section('page-breadcrumb')
    {{ __('Quote') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('quote.grid') }}" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @can('quote create')
            <a data-url="{{ route('quote.create', ['quote', 0]) }}" data-size="lg" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-title="{{ __('Create New Quote') }}" title="{{ __('Create') }}"class="btn btn-sm btn-primary btn-icon">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    </div>
@endsection
@section('filter')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('ID') }}</th>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{ __('Account') }}</th>
                                    <th scope="col" class="sort" data-sort="status">{{ __('Status') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Created At') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Amount') }}</th>
                                    <th scope="col" class="sort" data-sort="completion">{{ __('Assign User') }}</th>
                                    @if (Gate::check('quote create') ||
                                            Gate::check('quote show') ||
                                            Gate::check('quote edit') ||
                                            Gate::check('quote delete'))
                                        <th scope="col" class="text-end">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotes as $quote)
                                    <tr>
                                        <td>
                                            @can('quote edit')
                                                <a href="{{ route('quote.edit', $quote->id) }}" class="btn btn-outline-primary"
                                                    data-title="{{ __('Quote Details') }}">
                                                    {{ Modules\Sales\Entities\Quote::quoteNumberFormat($quote->quote_id) }}
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-outline-primary">
                                                    {{ Modules\Sales\Entities\Quote::quoteNumberFormat($quote->quote_id) }}</a>
                                            @endcan
                                        </td>
                                        <td> {{ ucfirst($quote->name) }}</td>
                                        <td>
                                            {{ ucfirst(!empty($quote->accounts) ? $quote->accounts->name : '--') }}
                                        </td>
                                        <td>
                                            @if ($quote->status == 0)
                                                <span class="badge bg-secondary p-2 px-3 rounded"
                                                    style="width: 79px;">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                            @elseif($quote->status == 1)
                                                <span class="badge bg-info p-2 px-3 rounded"
                                                    style="width: 79px;">{{ __(Modules\Sales\Entities\Quote::$status[$quote->status]) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="budget">{{ company_date_formate($quote->created_at) }}</span>
                                        </td>
                                        <td>
                                            <span class="budget">{{ currency_format_with_sym($quote->getTotal()) }}</span>
                                        </td>
                                        <td>
                                            <span class="col-sm-12"><span
                                                    class="text-m">{{ ucfirst(!empty($quote->assign_user) ? $quote->assign_user->name : '-') }}</span></span>
                                        </td>

                                        @if (Gate::check('quote create') ||
                                                Gate::check('quote show') ||
                                                Gate::check('quote edit') ||
                                                Gate::check('quote delete'))
                                            <td class="text-end">
                                                @can('quote create')
                                                    <div class="action-btn bg-secondary ms-2">
                                                        {!! Form::open([
                                                            'method' => 'get',
                                                            'route' => ['quote.duplicate', $quote->id],
                                                            'id' => 'duplicate-form-' . $quote->id,
                                                        ]) !!}

                                                        <a href="#"
                                                            class="mx-3 btn btn-sm align-items-center text-white show_confirm"
                                                            data-bs-toggle="tooltip" data-title="{{ __('Duplicate') }}"
                                                            title="{{ __('Duplicate') }}"
                                                            data-confirm="{{ __('You want to confirm this action') }}"
                                                            data-text="{{ __('Press Yes to continue or No to go back') }}"
                                                            data-confirm-yes="document.getElementById('duplicate-form-{{ $quote->id }}').submit();">
                                                            <i class="ti ti-copy"></i>
                                                            {!! Form::close() !!}
                                                        </a>
                                                    </div>
                                                @endcan

                                                @if ($quote->converted_salesorder_id == 0)
                                                    <div class="action-btn bg-success ms-2">
                                                        {!! Form::open([
                                                            'method' => 'get',
                                                            'route' => ['quote.convert', $quote->id],
                                                            'id' => 'quotes-form-' . $quote->id,
                                                        ]) !!}

                                                        <a href="#"
                                                            class="mx-3 btn btn-sm align-items-center text-white show_confirm"
                                                            data-bs-toggle="tooltip"
                                                            data-title="{{ __('Convert to Sales Order') }}"
                                                            title="{{ __('Conver to Sale Order') }}"
                                                            data-confirm="{{ __('You want to confirm convert to sales order.') }}"
                                                            data-text="{{ __('Press Yes to continue or No to go back') }}"
                                                            data-confirm-yes="document.getElementById('quotes-form-{{ $quote->id }}').submit();">
                                                            <i class="ti ti-exchange"></i>
                                                            {!! Form::close() !!}
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="action-btn bg-success ms-2">
                                                        <a href="{{ route('salesorder.show', $quote->converted_salesorder_id) }}"
                                                            class="mx-3 btn btn-sm align-items-center text-white"
                                                            data-bs-toggle="tooltip"
                                                            data-original-title="{{ __('Sales Order Details') }}"
                                                            title="{{ __('SalesOrders Details') }}">
                                                            <i class="fab fa-stack-exchange"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                                @can('quote show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('quote.show', $quote->id) }}"
                                                            data-size="md"class="mx-3 btn btn-sm align-items-center text-white "
                                                            data-bs-toggle="tooltip" title="{{ __('Quick View') }}"
                                                            data-title="{{ __('Quote Details') }}">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('quote edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ route('quote.edit', $quote->id) }}"
                                                            class="mx-3 btn btn-sm align-items-center text-white"
                                                            data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                            data-title="{{ __('Edit Quote') }}"><i
                                                                class="ti ti-pencil"></i></a>
                                                    </div>
                                                @endcan
                                                @can('quote delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['quote.destroy', $quote->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm   align-items-center text-white show_confirm"
                                                            data-bs-toggle="tooltip" title='Delete'>
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
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
    @endsection
    @push('scripts')
        <script>
            $(document).on('click', '#billing_data', function() {
                $("[name='shipping_address']").val($("[name='billing_address']").val());
                $("[name='shipping_city']").val($("[name='billing_city']").val());
                $("[name='shipping_state']").val($("[name='billing_state']").val());
                $("[name='shipping_country']").val($("[name='billing_country']").val());
                $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
            })

            $(document).on('change', 'select[name=opportunity]', function() {

                var opportunities = $(this).val();
                getaccount(opportunities);
            });

            function getaccount(opportunities_id) {
                $.ajax({
                    url: '{{ route('quote.getaccount') }}',
                    type: 'POST',
                    data: {
                        "opportunities_id": opportunities_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $('#amount').val(data.opportunitie.amount);
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
    @endpush
