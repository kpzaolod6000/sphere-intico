
<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <a href="{{route('account_type.index')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('account_type*') ? 'active' : '')}}">{{__('Account Type')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('account_industry.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('account_industry*') ? 'active' : '')}}">{{__('Account Industry')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('opportunities_stage.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('opportunities_stage*') ? 'active' : '')}}">{{__('Opportunities Stage')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('case_type.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('case_type*') ? 'active' : '')}}">{{__('Case Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('shipping_provider.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('shipping_provider*') ? 'active' : '')}}">{{__('Shipping Provider')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('salesdocument_type.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('salesdocument_type*') ? 'active' : '')}}">{{__('Document Type')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{ route('salesdocument_folder.index') }}" class="list-group-item list-group-item-action border-0 {{ (request()->is('salesdocument_folder*') ? 'active' : '')}}">{{__('Document Folder')}}<div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    </div>
</div>
