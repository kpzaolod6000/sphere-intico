{{ Form::open(array('route' => array('salesinvoice.sendmail',$invoice_id))) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{Form::label('name',__('To'),['class'=>'form-label']) }}
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('email',__('Email'),['class'=>'form-label']) }}
                    {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter Email'),'required'=>'required'))}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light"
            data-bs-dismiss="modal">{{__('Close')}}</button>
            {{Form::submit(__('Send'),array('class'=>'btn  btn-primary '))}}{{Form::close()}}
    </div>
{{ Form::close() }}
