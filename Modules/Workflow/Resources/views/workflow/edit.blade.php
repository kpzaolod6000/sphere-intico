@extends('layouts.main')
@section('page-title')
    {{ __('Workflow Edit') }}
@endsection
@section('page-breadcrumb')
    {{ __('Workflow') }},
    {{ __('Edit') }}
@endsection
@section('content')
    <div class="row">
        {{ Form::model($workflow, ['route' => ['workflow.update', $workflow->id], 'method' => 'PUT', 'class' => 'w-100']) }}
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h3>{{ __('Workflow Automation')}}</h3>
                    <div class="col-md-8">
                        <div class="form-group">
                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                            {{ Form::text('name', $workflow->name, ['class' => 'form-control', 'placeholder' => __('Enter Workflow Name'), 'required' => 'required']) }}
                            @error('name')
                                <small class="invalid-name" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>
                        <hr>
                    </div>
 
                    <div class="col-md-8">
                        
                        <h4>{{ __('Trigger')}}</h4>
                       
                        <div class="row">
                            <div class="form-group col-md-6 mt-3">
                                {{ Form::label('module_name', __('Module'), ['class' => 'form-label']) }}
                                {{ Form::select('module_name',$modules, $workflow->module_name, ['class' => 'form-control', 'required' => 'required', 'id' => 'module']) }}
                            </div>
                            @error('event')
                                <small class="invalid-name" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </small>
                            @enderror
    
                            <div class="form-group col-md-6 mt-3">
                                {{Form::label('event',__('Event'),['class'=>'form-label']) }}
                                {{ Form::select('event',[],null,['class'=>'form-control event','required'=>'required', 'id' => 'newevent']) }}
                            </div>
                            @error('event')
                                <small class="invalid-name" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </small>
                            @enderror
                        </div>
                    </div>
                     
                    <div class="col-md-8" id="preview_type">
                        <hr>
                        <div class="row">
                        <h4 class="col-md-6">{{ __('Condition')}}</h4>
                            <div class="col-md-6 d-flex justify-content-end my-3 mt-0">
                                <div class="all-button-box">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon m-1 float-end ms-2"
                                        id="add-field-btn" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ __('Condition Field') }}">
                                        <i class="ti ti-plus mr-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="repeater-container">
                            @php
                                $json_data_array = $workflow->json_data;
                                $data_array = json_decode($json_data_array);
                                $data_available = 0; 
                            @endphp

                            @if (!is_null($data_array) && (is_array($data_array) || count($data_array) > 0))
                            @php
                                $data_available = 1; 
                            @endphp
                                @foreach ($data_array as $keys => $array) 
                                    <div class="form-group-container" id="{{ 'form-group-container' . $keys }}">
                                        <div class="row">
                                            <div class="form-group col-md-4 ml-auto">
                                                @php
                                                    $preview_types = \Modules\Workflow\Entities\Workflow::workflow_dothis($workflow->event);
                                                    $events = \Modules\Workflow\Entities\Workflow::workflow_event($workflow->event);
                                                @endphp
                                                <select name={{ 'fields[' . $keys . '][preview_type]' }}
                                                    class='form-control font-style preview_type'>
                                                    @foreach ($preview_types as $key => $preview_type)
                                                        <option {{ $key == $array->preview_type ? 'selected' : '' }}
                                                            value="{{ $key }}">{{ $preview_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 ml-auto">
                                                <select name={{ 'fields[' . $keys . '][condition]' }}
                                                    class='form-control font-style'>
                                                    
                                                    @foreach (\Modules\Workflow\Entities\Workflow::$condition as $key => $value)
                                                        <option {{ $key == $array->condition ? 'selected' : '' }}
                                                            value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group col-md-4 ml-auto section_div">
                                                <input type="text" name="{{ 'fields[' . $keys . '][value]' }}"
                                                    value="{{ $array->value }}" class="form-control">
                                            </div>

                                            <div class="form-group col-md-1 ml-auto text-end">
                                                <a class="delete-icon"><i class="fas fa-trash text-danger"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group-container" id="form-group-container1">
                                    <div class="row">
                                        <div class="form-group col-md-4 ml-auto">
                                            <select name="fields[0][preview_type]"
                                                class='form-control font-style preview_type'>
                                                <option value="0" selected disabled>{{ __('Select Field Name') }}
                                                </option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group col-md-3 ml-auto">
                                            <select name="fields[0][condition]" class='form-control font-style'>
                                                <option value="" selected disabled>{{ __('Please Select') }}</option>
                                                @foreach (\Modules\Workflow\Entities\Workflow::$condition as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>  
                                        
                                        <div class="form-group col-md-4 ml-auto section_div">
                                            <input name="fields[0][value]" class="form-control"
                                                placeholder="{{ __('Enter text') }}">
                                        </div>

                                        <div class="form-group col-md-1 ml-auto text-end">
                                            <a class="delete-icon d-none disabled"><i class="fas fa-trash text-danger"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div class="col-md-8">
                        <hr>
                        <h4>{{ __('Action')}}</h4>
                        <div class="form-group col-md-12 mb-1">
                            <div>
                                {{ Form::select('do_this[]', $workflowdothis, !empty($workflow->do_this) ? explode(',', $workflow->do_this) : null, ['class' => 'form-control choices', 'id' => 'do_this', 'multiple' => 'multiple', 'required' => 'required']) }}
                            </div>
                        </div>

                        <div class="do_this_div mt-3">
                        </div>
                        <div class="modal-footer text-end" id ="form-submit-btn">
                            {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
    {{ Form::close() }}
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('Modules/Workflow/Resources/assets/summernote/summernote-bs4.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('Modules/Workflow/Resources/assets/summernote/summernote-bs4.css') }}">

    <script>
         function getModuleEvent(preselector = null,type = 0) {
                
            var modules = $('#module').val();
            $.ajax({
                url: '{{ route('workflow.modules') }}',
                type: 'POST',
                data: {
                    "module": modules,
                },
                success: function(response) {   
                    var eventSelect = $('.event');
                    eventSelect.empty();
                    eventSelect.append('<option value="" disabled selected>Please Select</option>');  
                    $.each(response.event_name, function(key, value) {   
                        if(key == preselector)
                        { 
                            $('.event').append('<option value="' + key + '" selected>' +
                                value + '</option>');
                        }
                        else
                        {
                            $('.event').append('<option value="' + key + '">' +
                                value + '</option>');
                        }
                    });
                    if(type == 0)
                    {
                        $('#newevent').trigger('change');
                    }
                }
            });
        }   
             
        $(document).ready(function() {
            $('#do_this').trigger('change')
            // $('#module').trigger('change')
             var preselector = '{{$workflow->event}}';  
             var data_available = '{{$data_available}}';     
                   
            getModuleEvent(preselector,data_available);
           
            $('#form-group-container0 .delete-icon').addClass('d-none');
        });

        $(document).on('change', '#do_this', function() {
            var selectedOptions = $(this).find('option');
            var selectedValues = [];

            $.each(selectedOptions, function() {
                selectedValues.push($(this).val());
            });
            var workflow_id = '{{ $workflow->id }}'

            $.ajax({
                url: '{{ route('workflow.attribute') }}',
                type: 'POST',
                data: {
                    "attribute_id": selectedValues,
                    "_token": "{{ csrf_token() }}",
                    "workflow_id": workflow_id,
                },
                beforeSend: function() {
                    $(".loader-wrapper").removeClass('d-none');
                },
                success: function(response) {
                    $(".loader-wrapper").addClass('d-none');
                    if (response != false) {
                        $('.do_this_div').html(response.html);
                    } else {
                        $('.do_this_div').html('');
                        toastrs('Error', 'Something went wrong please try again !', 'error');
                    }
                }
            });
        });

        $(document).on("change", "#newevent", function() {
            
            var eventId =  $(this).val();
            $.ajax({
                url: '{{ route('workflow.getfielddata') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "event_id": eventId,
                },
                success: function(response) {
                    var container = $('#repeater-container .form-group-container:first');
                    $('#repeater-container .form-group-container').not(container).remove();

                    $('#repeater-container .form-group-container .form-control').val('')

                    $('.preview_type').empty();
                    $.each(response.data, function(key, value) {
                        $('.preview_type').append('<option value="' + key + '">' +
                            value + '</option>');
                    });

                }
            });
        });

        $(document).on('change', '.preview_type', function() {
            updateSectionDiv($(this));
        });

        </script>
        
        @if (!is_null($data_array) && (is_array($data_array) || count($data_array) > 0))
            <script>
                $(document).ready(function() {
                    $('.preview_type').each(function() {
                        updateSectionDiv($(this));
                    });
                });
            </script>
        @endif

        <script>

        function updateSectionDiv(previewTypeElement) {
            var teamSection = previewTypeElement.closest('.form-group-container');
            var input_div = teamSection.find('.section_div');
            var input_name = input_div.find('.form-control').attr('name');
            var values = input_div.find('.form-control').val();
            var workmodulId = previewTypeElement.val();

            $.ajax({
                url: '{{ route('workflow.getcondition') }}',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "workmodule_id": workmodulId,
                    "input_name": input_name,
                },
                success: function(response) {
                    if (response != false) {
                        $("#loader").addClass('d-none');
                        input_div.html(response.html);
                        if (values.length > 0) {
                            input_div.find('.form-control').val(values);
                        }
                    } else {
                        $('.section_div').html('');
                        toastrs('Error', 'Something went wrong please try again !', 'error');
                    }
                },
            });
        }
    </script>

    <script> 
        $(document).ready(function() {
          let plusFieldIndex = 0; 

          $(".form-group-container").each(function() {
              const index = parseInt($(this).attr("id").replace("form-group-container", ""));
              if (index > plusFieldIndex) {
                  plusFieldIndex = index;
              }
          });

          // Function to add a new field container
          function addNewField(index) {
              const newContainer = $("#repeater-container").find(".form-group-container").first().clone();

              plusFieldIndex++;
              newContainer.attr("id", "form-group-container" + plusFieldIndex); 
              newContainer.find("select[name^='fields[0]'][name$='[preview_type]']").attr("name",
                  "fields[" + plusFieldIndex + "][preview_type]");
              newContainer.find("select[name^='fields[0]'][name$='[condition]']").attr("name", "fields[" +
                  plusFieldIndex + "][condition]");
              newContainer.find(".section_div .form-control").attr("name", "fields[" +
                  plusFieldIndex + "][value]");
                //   console.log("fields[" +
                //   plusFieldIndex + "][value]");

              newContainer.find('.delete-icon').removeClass('disabled'); 
              newContainer.find('.delete-icon').removeClass('d-none'); 

              $("#repeater-container").append(newContainer);
          }

          // Add a new field when the button is clicked
          $("#add-field-btn").on("click", function() {
              addNewField(plusFieldIndex);
          });

          // Remove a field container when the delete icon is clicked
          $(document).on("click", ".delete-icon:not(.disabled)", function() {
              var container = $(this).closest('.form-group-container');

              if (container.attr("id") !== "form-group-container0") {
                  container.remove();
              }
          });
          
        });
    </script>

    <script>
         $(document).on("change", "#module", function() { 
            getModuleEvent();
        });               
    </script>
@endpush
