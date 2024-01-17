{{ Form::open(['url' => 'indicator', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch_id', !empty(company_setting('hrm_branch_name')) ? company_setting('hrm_branch_name') : __('Branch'), ['class' => 'form-label']) }}
                {{ Form::select('branch_id', $brances, null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Branch']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('department_id', !empty(company_setting('hrm_department_name')) ? company_setting('hrm_department_name') : __('Department'), ['class' => 'form-label']) }}
                {{ Form::select('department_id', [], null, ['class' => 'form-control ', 'id' => 'department_id', 'required' => 'required', 'placeholder' => 'Select Department']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('designation_id', !empty(company_setting('hrm_designation_name')) ? company_setting('hrm_designation_name') : __('Designation'), ['class' => 'form-label']) }}
                {{ Form::select('designation_id', [], null, ['class' => 'form-control ', 'id' => 'designation_id', 'required' => 'required', 'placeholder' => 'Select Designation']) }}
            </div>
        </div>
    </div>
    <div class="row">
        @foreach ($performance_types as $performance_type)
            <div class="col-md-12 mt-3">
                <h6>{{ $performance_type->name }}</h6>
                <hr class="mt-0">
            </div>

            @foreach ($performance_type->types as $types)
                <div class="col-6">
                    {{ $types->name }}
                </div>
                <div class="col-6">
                    <fieldset id='demo1' class="rate">
                        <input class="stars" type="radio" id="technical-5-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="5" />
                        <label class="full" for="technical-5-{{ $types->id }}" title="Awesome - 5 stars"></label>
                        <input class="stars" type="radio" id="technical-4-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="4" />
                        <label class="full" for="technical-4-{{ $types->id }}"
                            title="Pretty good - 4 stars"></label>
                        <input class="stars" type="radio" id="technical-3-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="3" />
                        <label class="full" for="technical-3-{{ $types->id }}" title="Meh - 3 stars"></label>
                        <input class="stars" type="radio" id="technical-2-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="2" />
                        <label class="full" for="technical-2-{{ $types->id }}"
                            title="Kinda bad - 2 stars"></label>
                        <input class="stars" type="radio" id="technical-1-{{ $types->id }}"
                            name="rating[{{ $types->id }}]" value="1" />
                        <label class="full" for="technical-1-{{ $types->id }}"
                            title="Sucks big time - 1 star"></label>
                    </fieldset>
                </div>
            @endforeach
        @endforeach
    </div>

</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
<script type="text/javascript">
    $(document).on('change', '#branch_id', function() {
        var branch_id = $(this).val();
        getDepartment(branch_id);
    });

    function getDepartment(branch_id) {
        var data = {
            "branch_id": branch_id,
            "_token": "{{ csrf_token() }}",
        }

        $.ajax({
            url: '{{ route('employee.getdepartment') }}',
            method: 'POST',
            data: data,
            success: function(data) {
                $('#department_id').empty();
                $('#department_id').append(
                    '<option value="" disabled>{{ __('Select Department') }}</option>');

                $.each(data, function(key, value) {
                    $('#department_id').append('<option value="' + key + '">' + value +
                    '</option>');
                });
                $('#department_id').val('');
            }
        });
    }

    $(document).on('change', 'select[name=department_id]', function() {
        var department_id = $(this).val();
        getDesignation(department_id);
    });

    function getDesignation(did) {
        $.ajax({
            url: '{{ route('employee.getdesignation') }}',
            type: 'POST',
            data: {
                "department_id": did,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $('#designation_id').empty();
                $('#designation_id').append(
                    '<option value="">{{ __('Select Designation') }}</option>');
                $.each(data, function(key, value) {
                    $('#designation_id').append('<option value="' + key + '">' + value +
                        '</option>');
                });
            }
        });
    }
</script>
