@extends('layouts.main')
@section('page-title')
    {{ __('Manage Sales Order') }}
@endsection
@section('page-breadcrumb')
    {{ __('Sales Order') }}
@endsection
@section('page-action')
<div>
    <a href="{{ route('salesorder.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
    title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>
    @can('salesorder create')
        <a data-size="lg" data-url="{{ route('salesorder.create', ['salesorder', 0]) }}" data-ajax-popup="true"
            data-bs-toggle="tooltip" data-title="{{ __('Create New Sales Order') }}" title="{{ __('Create') }}"
            class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
@section('content')
<div class="row">
<div class="col-sm-12">
    <div class="row">
        @foreach ($salesorders as $salesorder)
        <div class="col-lg-4">
            <div class="card hover-shadow-lg">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-10">
                            <h6 class="mb-0">
                                @can('salesorder show')
                                    <a href="{{ route('salesorder.show', $salesorder->id) }}"
                                        class="" data-title="{{ __('Quote Details') }}">
                                        {{ Modules\Sales\Entities\SalesOrder::salesorderNumberFormat($salesorder->salesorder_id) }}
                                    </a>
                                @else
                                    <a href="#"
                                        class="" data-title="{{ __('Quote Details') }}">
                                        {{ Modules\Sales\Entities\SalesOrder::salesorderNumberFormat($salesorder->salesorder_id) }}
                                    </a>
                                @endcan
                            </h6>
                        </div>
                        <div class="col-2 text-end">
                            <div class="actions">
                                <div class="dropdown">
                                    <a href="#" class="action-item" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @can('salesorder edit')
                                            <a href="{{ route('salesorder.edit', $salesorder->id) }}" class="dropdown-item" data-bs-toggle="tooltip" data-title="{{__('Edit Quote')}}"><i class="ti ti-pencil"></i>{{__('Edit')}}</a>
                                        @endcan
                                        @can('salesorder delete')
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['salesorder.destroy', $salesorder->id]]) !!}
                                                <a href="#!" class="dropdown-item show_confirm" data-bs-toggle="tooltip">
                                                    <i class="ti ti-trash"></i>{{ __('Delete') }}
                                                </a>
                                            {!! Form::close() !!}
                                        @endcan
                                        @can('salesorder show')
                                            <a href="{{ route('salesorder.show', $salesorder->id) }}" data-size="md"class="dropdown-item" data-bs-toggle="tooltip" data-title="{{__('Details')}}">
                                                <i class="ti ti-eye"></i>{{__('View')}}
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 border border-dashed">

                        @if ($salesorder->status == 0)
                            <span class="badge bg-secondary p-2 px-3 rounded" style="width: 79px;">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                        @elseif($salesorder->status == 1)
                            <span class="badge bg-info p-2 px-3 rounded" style="width: 79px;">{{ __(Modules\Sales\Entities\SalesOrder::$status[$salesorder->status]) }}</span>
                        @endif
                        <div class="row align-items-center mt-3">
                            <div class="col-6">
                                <h6 class="mb-0">{{currency_format_with_sym($salesorder->getTotal())}}</h6>
                                <span class="text-sm text-muted">{{__('Total Amount')}}</span>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-0">{{currency_format_with_sym($salesorder->getSubTotal())}}</h6>
                                <span class="text-sm text-muted">{{__('Sub Total')}}</span>
                            </div>
                        </div>
                        <div class="row align-items-center mt-3">
                            <div class="col-6">
                                <h6 class="mb-0">{{currency_format_with_sym($salesorder->getTotalTax())}}</h6>
                                <span class="text-sm text-muted">{{__('Total Tax')}}</span>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-0">{{company_date_formate($salesorder->created_at)}}</h6>
                                <span class="text-sm text-muted">{{__('Start Date')}}</span>
                             </div>
                        </div>
                    </div>
                    @if (\Auth::user()->type != 'Client')
                        <div class="user-group pt-2">
                                <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $salesorder->assign_user->name }}"
                                    @if ($salesorder->assign_user->avatar) src="{{ get_file($salesorder->assign_user->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                    class="rounded-circle " width="25" height="25">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @auth('web')
            @can('salesorder create')
            <div class="col-md-3">
                <a href="#" data-url="{{ route('salesorder.create', ['salesorder', 0]) }}" data-size="lg" data-ajax-popup="true" class="  btn-addnew-project"
                    data-title="{{ __('Create New Sales Order') }}" style="padding: 90px 10px;">
                    <div class="badge bg-primary proj-add-icon">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6 class="mt-4 mb-2">{{ __('Create New Sales Order') }}</h6>
                    <p class="text-muted text-center">{{ __('Click here to Sales Order') }}</p>
                </a>
            </div>
            @endcan
        @endauth
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
                url: '{{ route('salesorder.getaccount') }}',
                type: 'POST',
                data: {
                    "opportunities_id": opportunities_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
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
@endpush

