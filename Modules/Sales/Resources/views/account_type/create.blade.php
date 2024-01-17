{{Form::open(array('url'=>'account_type','method'=>'post'))}}
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{Form::label('name',__('Account Type'),['class'=>'form-label']) }}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Account Type'),'required'=>'required'))}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light"
            data-bs-dismiss="modal">{{__('Close')}}</button>
            {{Form::submit(__('Save'),array('class'=>'btn  btn-primary '))}}{{Form::close()}}
    </div>
{{Form::close()}}
