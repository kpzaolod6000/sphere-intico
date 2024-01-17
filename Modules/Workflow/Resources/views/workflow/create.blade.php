   {{Form::open(array('url'=>'workflow','method'=>'post'))}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Workflow Name'),'required'=>'required'))}}
                    @error('name')
                    <small class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                    @enderror
                </div>
            </div>
            <hr>
            <h4>{{ __('Trigger')}}</h4>
            <div class="col-md-12">
                <div class="row">
                    <div class="form-group col-md-6 mt-3">
                        {{ Form::label('module_name', __('Module'), ['class' => 'form-label']) }}
                        {{ Form::select('module_name',$modules, null, ['class' => 'form-control', 'required' => 'required', 'id' => 'module']) }}
                    </div>
                    
                    @error('module')
                    <small class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                    @enderror
                
                    <div class="form-group col-md-6 mt-3">
                        {{Form::label('event',__('Event'),['class'=>'form-label']) }}
                        {{ Form::select('event',[],null,['class'=>'form-control event','required'=>'required']) }}
                    </div>
                    @error('event')
                    <small class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                    @enderror
                </div>
            </div>
            <hr>
            <h4>{{ __('Action')}}</h4>
            <div class="form-group col-md-12 mb-1">
                <div>
                    {{ Form::select('do_this[]', $workflowdothis,null, ['class' => 'form-control choices', 'id' => 'choices-multiple', 'multiple' => '', 'required' => 'required']) }}
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
    </div>
    {{Form::close()}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectField = document.getElementById('choices-multiple');
    
            selectField.addEventListener('change', function() {
                const placeholderOption = selectField.querySelector('option[value=""]');
                if (placeholderOption) {
                    placeholderOption.disabled = true;
                }
            });
        });

        $(document).on("change", "#module", function() {
            var modules = $(this).val();
             
            $.ajax({
                url: '{{ route('workflow.modules') }}',
                type: 'POST',
                data: {
                    "module": modules,
                },
                success: function(response) {
 
                    $('.event').empty();
                    $.each(response.event_name, function(key, value) {
                        $('.event').append('<option value="' + key + '">' +
                            value + '</option>');
                    });

                }
            });
        });

    </script>