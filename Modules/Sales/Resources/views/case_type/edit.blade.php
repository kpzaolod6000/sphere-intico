{{Form::model($caseType, array('route' => array('case_type.update', $caseType->id), 'method' => 'PUT')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Account Type'),'required'=>'required'))}}
                    @error('name')
                    <span class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light"
            data-bs-dismiss="modal">{{__('Close')}}</button>
            {{Form::submit(__('Update'),array('class'=>'btn  btn-primary '))}}{{Form::close()}}
    </div>
{{Form::close()}}
