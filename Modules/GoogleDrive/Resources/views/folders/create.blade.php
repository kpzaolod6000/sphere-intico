{{Form::open(array('route' => array('store.new.folder',[ $module , $folder_id]), 'method' => 'post')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('folder_name',__('Folder Name'),['class'=>'form-label']) }}
                    {{Form::text('folder_name',null,array('class'=>'form-control','placeholder'=>__('Enter Folder name'),'required'=>'required'))}}
                    @error('folder_name')
                    <small class="invalid-folder_name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('create')}}" class="btn  btn-primary">
    </div>
{{Form::close()}}