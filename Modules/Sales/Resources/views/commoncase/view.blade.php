<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <table class="table modal-table">
                <tr>
                    <th>{{__('Name')}}</th>
                    <td>{{ $commonCase->name }}</td>
                </tr>
                <tr>
                    <th>{{__('Number')}}</th>
                    <td>{{ $commonCase->number}}</td>
                </tr>
                <tr>
                    <th>{{__('Status')}}</th>
                    <td>
                        @if($commonCase->status == 0)
                            <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$commonCase->status]) }}</span>
                        @elseif($commonCase->status == 1)
                            <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$commonCase->status]) }}</span>
                        @elseif($commonCase->status == 2)
                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$commonCase->status]) }}</span>
                        @elseif($commonCase->status == 3)
                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$commonCase->status]) }}</span>
                        @elseif($commonCase->status == 4)
                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$commonCase->status]) }}</span>
                        @elseif($commonCase->status == 5)
                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$status[$commonCase->status]) }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{__('Account')}}</th>
                    <td>{{ !empty($commonCase->accounts)?$commonCase->accounts->name:'-'  }}</td>
                </tr>
                <tr>
                    <th>{{__('Priority')}}</th>
                    <td>
                        @if($commonCase->priority == 0)
                            <span class="badge bg-primary p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$commonCase->priority]) }}</span>
                        @elseif($commonCase->priority == 1)
                            <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$commonCase->priority]) }}</span>
                        @elseif($commonCase->priority == 2)
                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$commonCase->priority]) }}</span>
                        @elseif($commonCase->priority == 3)
                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\CommonCase::$priority[$commonCase->priority]) }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{__('Contacts')}}</th>
                    <td>{{ !empty($commonCase->contacts->name)?$commonCase->contacts->name:'-' }}</td>
                </tr>
                <tr>
                    <th>{{__('Type')}}</th>
                    <td>{{ !empty($commonCase->types)?$commonCase->types->name:'-' }}</td>
                </tr>
                <tr>
                    <th>{{__('Description')}}</th>
                    <td>{{ $commonCase->description }}</td>
                </tr>
                <tr>
                    <th>{{ __('Assigned User') }}</th>
                    <td>{{ !empty($commonCase->assign_user)?$commonCase->assign_user->name:'-'}}</td>
                </tr>
                <tr>
                    <th>{{__('Created')}}</th>
                    <td>{{company_date_formate($commonCase->created_at)}}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="w-100 text-end pr-2">
        @can('case edit')
        <div class="action-btn bg-info ms-2">
            <a href="{{ route('commoncases.edit',$commonCase->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center text-white " data-bs-toggle="tooltip" data-title="{{__('Case Edit')}}"title="{{__('Edit')}}"><i class="ti ti-pencil   "></i>
            </a>
        </div>

        @endcan
    </div>
</div>


