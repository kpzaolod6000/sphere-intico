
<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <a href="{{route('ticket-category.index')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('ticket-category*') ? 'active' : '')}}">{{__('Support Category')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

        <a href="{{route('knowledge-category.index')}}" class="list-group-item list-group-item-action border-0 {{ (request()->is('knowledge-category*') ? 'active' : '')}}">{{__('KnowledgeBase Category')}} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    </div>
</div>
