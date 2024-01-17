{{Form::open(array('url'=>'contact','method'=>'post','enctype'=>'multipart/form-data'))}}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'contact','module'=>'Sales'])
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
                    {{Form::label('account',__('Account'),['class'=>'form-label']) }}
                    {!!Form::select('account', $account, $id,array('class' => 'form-control')) !!}
                </div>
            </div>

        @else
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('account',__('Account'),['class'=>'form-label']) }}
                    {!! Form::select('account', $account, null,array('class' => 'form-control')) !!}
                </div>
            </div>
        @endif
        <div class="col-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label']) }}
                {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('phone',__('Phone'),['class'=>'form-label']) }}
                {{Form::text('phone',null,array('class'=>'form-control','placeholder'=>__('Enter Phone'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('contactaddress',__('Address'),['class'=>'form-label']) }}
                {{Form::text('contact_address',null,array('class'=>'form-control','placeholder'=>__('Address'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('contactaddress',__('City'),['class'=>'form-label']) }}
                {{Form::text('contact_city',null,array('class'=>'form-control','placeholder'=>__('City'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('contactaddress',__('State'),['class'=>'form-label']) }}
                {{Form::text('contact_state',null,array('class'=>'form-control','placeholder'=>__('State'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('contact_postalcode',__('Postal Code'),['class'=>'form-label']) }}
                {{Form::number('contact_postalcode',null,array('class'=>'form-control','placeholder'=>__('Postal Code'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('contact_country',__('Country'),['class'=>'form-label']) }}
                {{Form::text('contact_country',null,array('class'=>'form-control','placeholder'=>__('Country'),'required'=>'required'))}}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{Form::label('user_id',__('Assign User'),['class'=>'form-label']) }}
                {!! Form::select('user_id', $user, null,array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                {{Form::label('description',__('Description'),['class'=>'form-label']) }}
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
