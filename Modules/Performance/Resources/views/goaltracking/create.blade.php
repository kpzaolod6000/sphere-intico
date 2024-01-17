{{ Form::open(['url' => 'goaltracking', 'method' => 'post']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'goal tracking','module'=>'Performance'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('branch', !empty(company_setting('hrm_branch_name')) ? company_setting('hrm_branch_name') : __('Branch'), ['class' => 'col-form-label']) }}
                {{ Form::select('branch', $brances, null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('goal_type', __('GoalTypes'), ['class' => 'col-form-label']) }}
                {{ Form::select('goal_type', $goalTypes, null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('start_date', null, [
                    'class' => 'form-control ',
                    'autocomplete' => 'off',
                    'required' => 'required',
                ]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control ', 'autocomplete' => 'off', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label']) }}
                {{ Form::text('subject', null, ['class' => 'form-control', 'placeholder' => 'Enter subject', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('target_achievement', __('Target Achievement'), ['class' => 'col-form-label']) }}
                {{ Form::text('target_achievement', null, ['class' => 'form-control', 'placeholder' => 'Enter target achievement']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => 'Enter description']) }}
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder')
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
