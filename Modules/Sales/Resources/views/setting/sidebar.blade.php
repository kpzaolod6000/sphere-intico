@can('sales manage')
    <a href="#sales-print-sidenav" class="list-group-item list-group-item-action">
        {{ __('Quote Print Settings') }}
        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
     </a>
     <a href="#salesorder-print-sidenav" class="list-group-item list-group-item-action">
        {{ __('Sales Order Print Settings') }}
        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
     </a>
     <a href="#salesinvoice-print-sidenav" class="list-group-item list-group-item-action">
        {{ __('Sales Invoice Print Settings') }}
        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
     </a>
@endcan
