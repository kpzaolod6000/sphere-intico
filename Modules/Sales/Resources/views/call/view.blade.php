<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <table class="table modal-table">
                <tbody>
                    <tr>
                        <th>{{__('Name')}}</th>
                        <td>{{ $call-> name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Status')}}</th>
                        <td>
                            @if($call->status == 0)
                                <span class="badge bg-success p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}
                            @elseif($call->status == 1)
                                <span class="badge bg-info p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}
                            @elseif($call->status == 2)
                                <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}
                            @elseif($call->status == 3)
                                <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}
                            @elseif($call->status == 4)
                                <span class="badge bg-danger p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}
                            @elseif($call->status == 5)
                                <span class="badge bg-warning p-2 px-3 rounded">{{ __(Modules\Sales\Entities\Call::$status[$call->status]) }}
                            @endif
                        </dd>
                    </tr>
                    <tr>
                        <th>{{__('Direction')}}</th>
                        <td>
                            @if($call->direction == 0)
                                {{ __(Modules\Sales\Entities\Call::$direction[$call->direction]) }}
                            @elseif($call->direction == 1)
                                {{ __(Modules\Sales\Entities\Call::$direction[$call->direction]) }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('Start Date')}}</th>
                        <td>{{company_date_formate($call->start_date)}}</td>
                    </tr>
                    <tr>
                        <th>{{__('End Date')}}</th>
                        <td>{{company_date_formate($call->end_date)}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Parent')}}</th>
                        <td>{{ $call->parent }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Parent User')}}</th>
                        <td>{{ $call->getparent($call->parent,$call->parent_id) }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Description')}}</th>
                        <td>{{ $call->description }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Attendees User')}}</th>
                        <td>{{ !empty($call->attendees_users->name)?$call->attendees_users->name:'-' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Attendees Contact')}}</th>
                        <td>{{ !empty($call->attendees_contacts->name)?$call->attendees_contacts->name:'-' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Attendees Lead')}}</th>
                        <td>{{ !empty($call->attendees_leads()->name)?$call->attendees_leads()->name:'-' }}</td>
                    </tr>
                    <tr>
                        <th>{{__('Assigned User')}}</th>
                        <td>{{ !empty($call->assign_user)?$call->assign_user->name:''}}</td>
                    </tr>
                    <tr>
                        <th>{{__('Created')}}</th>
                        <td>{{company_date_formate($call->created_at)}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="w-100 text-end pr-2">
        @can('call edit')
        <div class="action-btn bg-info ms-2">
            <a href="{{ route('call.edit',$call->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center text-white"data-bs-toggle="tooltip" data-title="{{__('Edit Call')}}" title="{{__('Edit')}}"><i class="ti ti-pencil"></i>
            </a>
        </div>
        @endcan
    </div>
</div>


