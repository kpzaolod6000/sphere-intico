<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Name')}}</th>
                        <td>{{ $opportunities->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Account')}}</th>
                        <td>{{ !empty($opportunities->accounts)?$opportunities->accounts->name:'-'  }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Stage')}}</th>
                        <td>{{ !empty($opportunities->stages)?$opportunities->stages->name:'-'}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Amount')}}</th>
                        <td>{{currency_format_with_sym( $opportunities->amount)}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Probability')}}</th>
                        <td>{{ $opportunities->probability }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Close Date')}}</th>
                        <td>{{company_date_formate($opportunities->close_date)}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Contacts')}}</th>
                        <td>{{ !empty($opportunities->contacts)?$opportunities->contacts->name:'-'}}</td>
                    </tr>
                    @if(module_is_active('Lead'))
                    <tr>
                        <th>{{__('Lead Source')}}</th>
                        <td>{{ !empty($opportunities->leadsource)?$opportunities->leadsource->name:'-'}}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>{{__('Description')}}</th>
                        <td>{{ $opportunities->description }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Assigned User')}}</th>
                        <td>{{ !empty($opportunities->assign_user)?$opportunities->assign_user->name:'-'}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Created')}}</th>
                        <td>{{company_date_formate($opportunities->created_at )}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="w-100 text-end pr-2">
        @can('opportunities edit')
            <div class="action-btn bg-info ms-2">
                <a href="{{ route('opportunities.edit',$opportunities->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center text-white" data-title="{{__('Opportunities Edit')}}" data-bs-toggle="tooltip"title="{{__('Edit')}}"><i class="ti ti-pencil"></i>
                </a>
            </div>

        @endcan
    </div>
</div>
