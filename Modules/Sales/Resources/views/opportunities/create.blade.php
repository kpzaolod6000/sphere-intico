{{Form::open(array('url'=>'opportunities','method'=>'post','enctype'=>'multipart/form-data'))}}
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'opportunities','module'=>'Sales'])
            @endif
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                </div>
            </div>
            @if($type == 'account')
                <div class="col-6">
                    <div class="form-group">
                        {{Form::label('account',__('Account Name'),['class'=>'form-label']) }}
                        {!!Form::select('account', $account_name, $id,array('class' => 'form-control','required'=>'required','placeholder'=>'Select Account')) !!}
                    </div>
                </div>
            @else
                <div class="col-6">
                    <div class="form-group">
                        {{Form::label('account',__('Account'),['class'=>'form-label']) }}
                        {!!Form::select('account', $account_name, null,array('class' => 'form-control','required'=>'required','placeholder'=>'Select Account')) !!}
                    </div>
                </div>
            @endif
            @if($type == 'contact')
                <div class="col-6">
                    <div class="form-group">
                        {{Form::label('contact',__('Contacts'),['class'=>'form-label']) }}
                        {!!Form::select('contact', $contact, $id,array('class' => 'form-control')) !!}
                    </div>
                </div>
            @else
                <div class="col-6">
                    <div class="form-group">
                        {{Form::label('contact',__('Contacts'),['class'=>'form-label']) }}
                        {!!Form::select('contact', $contact, null,array('class' => 'form-control')) !!}
                    </div>
                </div>
            @endif
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('stage',__('Opportunities Stage'),['class'=>'form-label']) }}
                    {!!Form::select('stage', $opportunities_stage, null,array('class' => 'form-control','required'=>'required','placeholder'=>'select Opportunities Stage')) !!}
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('amount',__('Amount'),['class'=>'form-label']) }}
                    {!! Form::number('amount', null,array('class' => 'form-control ','placeholder'=>__('Enter Amount'),'required'=>'required')) !!}
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('probability',__('Probability'),['class'=>'form-label']) }}
                    {{Form::number('probability',null,array('class'=>'form-control','placeholder'=>__('Enter Probability'),'required'=>'required'))}}
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    {{Form::label('close_date',__('Close Date'),['class'=>'form-label']) }}
                    {{Form::date('close_date',date('Y-m-d'),array('class'=>'form-control','placeholder'=>__('Enter close date'),'required'=>'required'))}}
                </div>
            </div>
            @if(module_is_active('Lead'))
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('lead_source',__('Lead Source'),['class'=>'form-label']) }}
                    {!! Form::select('lead_source', $leadsource, null,array('class' => 'form-control','placeholder'=>__('Select Lead Source'),'required'=>'required')) !!}
                </div>
            </div>
            @endif
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('user',__('Assign User')) }}
                    {!! Form::select('user', $user, null,array('class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    {{Form::label('Description',__('Description'),['class'=>'form-label']) }}
                    {{Form::textarea('description',null,array('class'=>'form-control','rows'=>2,'placeholder'=>__('Enter Description')))}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light"
            data-bs-dismiss="modal">{{__('Close')}}</button>
            {{Form::submit(__('Save'),array('class'=>'btn btn-primary '))}}
    </div>
{{Form::close()}}
