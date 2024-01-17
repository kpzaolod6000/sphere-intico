{{Form::model($documentFolder, array('route' => array('salesdocument_folder.update', $documentFolder->id), 'method' => 'PUT')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'form-label'])}}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
                    @error('name')
                    <span class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                {{Form::label('parent',__('parent'),['class'=>'form-label']) }}
                {!! Form::select('parent', $parent, null,array('class' => 'form-control')) !!}
                </div>
                @error('parent')
                <span class="invalid-parent" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="col-12">
                <div class="form-group">
                    {{Form::label('description',__('Description'),['class'=>'form-label']) }}
                    {!! Form::textarea('description',null,array('class' =>'form-control ','rows'=>3)) !!}
                    @error('description')
                    <span class="invalid-description" role="alert">
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
