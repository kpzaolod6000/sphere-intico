{{ Form::open(['url' => 'meeting', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn', [
                'template_module' => 'meeting',
                'module' => 'Sales',
            ])
        @endif
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                {!! Form::select('status', $status, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Select Status',
                ]) !!}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                {!! Form::date('start_date', date('Y-m-d'), [
                    'class' => 'form-control',
                    'placeholder' => 'Start Date',
                    'required' => 'required',
                ]) !!}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                {!! Form::date('end_date', date('Y-m-d'), [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'End Date',
                ]) !!}
            </div>
        </div>
        <div class="col-6" data-name="parent">
            <div class="form-group">
                {{ Form::label('parent', __('Parent'), ['class' => 'form-label']) }}
                {!! Form::select('parent', $parent, null, [
                    'class' => 'form-control',
                    'name' => 'parent',
                    'id' => 'parent',
                    'required' => 'required',
                    'placeholder' => __('Select Parent'),
                ]) !!}
            </div>
        </div>
        <div class="col-6" data-name="parent">
            <div class="form-group">
                {{ Form::label('parent_id', __('Parent User'), ['class' => 'form-label']) }}
                <select class="form-control" name="parent_id" id="parent_id">

                </select>
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 2, 'placeholder' => __('Enter Description')]) }}
            </div>
        </div>
        @if ($type == 'account')
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('account', __('Account Name'), ['class' => 'form-label']) }}
                {!! Form::select('account', $account_name, $id, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Select Account',
                ]) !!}
            </div>
        </div>
    @else
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                {!! Form::select('account', $account_name, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Select Account',
                ]) !!}
            </div>
        </div>
    @endif
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('user', __('Assign User'), ['class' => 'form-label']) }}
                {!! Form::select('user', $user, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Assign User'),
                ]) !!}
            </div>
        </div>
        <div class="col-12">
            <hr class="mt-2 mb-2">
            <h6>{{ __('Attendees') }}</h6>
        </div>

        <div class="col-6">
            <div class="form-group">
                {{ Form::label('attendees_user', __('Attendees User'), ['class' => 'form-label']) }}
                {!! Form::select('attendees_user', $user, null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => __('Select Attendees User'),
                ]) !!}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('attendees_contact', __('Attendees Contact'), ['class' => 'form-label']) }}
                {!! Form::select('attendees_contact', $attendees_contact, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{ Form::label('attendees_lead', __('Attendees Lead'), ['class' => 'form-label']) }}
                {!! Form::select('attendees_lead', $attendees_lead, null, ['class' => 'form-control']) !!}
                @if (module_is_active('Lead'))
                    @if (empty($attendees_lead == null))
                        <div class=" text-xs">
                            {{ __('Please create first.') }}<a
                                href="{{ route('leads.index') }}"><b>{{ __('Lead') }}</b></a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        @if (module_is_active('Calender') && company_setting('google_calendar_enable') == 'on')
            @include('calender::setting.synchronize')
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Save'), ['class' => 'btn  btn-primary ']) }}{{ Form::close() }}
</div>
{{ Form::close() }}
