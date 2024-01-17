@extends('layouts.main')

@section('page-title')
    {{ __('Create Ticket') }}
@endsection

@section('page-breadcrumb')
    {{ __('Tickets') }},{{ __('Create') }}
@endsection
@push('css')
<link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">

@endpush
@section('content')
    <form action="{{ route('support-tickets.store') }}" class="mt-3" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h6>{{ __('Ticket Information') }}</h6>
                        <div class="text-end">
                            @if (module_is_active('AIAssistant'))
                                @include('aiassistant::ai.generate_ai_btn', [
                                    'template_module' => 'ticket',
                                    'module' => 'SupportTicket',
                                ])
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4 mt-2">
                                <div class="form-group">
                                    <label class="form-label" for="example3cols3Input">{{ __('Account Type') }}</label>
                                    <div class="row ms-1">
                                        <div class="form-check col-md-3">
                                            <input class="form-check-input" type="radio" name="account_type"
                                                value="custom" id="account_type_custom" checked="checked">
                                            <label class="form-check-label pointer" for="account_type_custom">
                                                {{ __('Custom') }}
                                            </label>
                                        </div>
                                        <div class="form-check col-md-3">
                                            <input class="form-check-input" type="radio" name="account_type"
                                                value="staff" id="account_type_staff">
                                            <label class="form-check-label pointer" for="account_type_staff">
                                                {{ __('Staff') }}
                                            </label>
                                        </div>
                                        <div class="form-check col-md-3">
                                            <input class="form-check-input" type="radio" name="account_type"
                                                value="client" id="account_type_client">
                                            <label class="form-check-label pointer" for="account_type_client">
                                                {{ __('Client') }}
                                            </label>
                                        </div>
                                        <div class="form-check col-md-3">
                                            <input class="form-check-input" type="radio" name="account_type"
                                                value="vendor" id="account_type_vendor">
                                            <label class="form-check-label pointer" for="account_type_vendor">
                                                {{ __('Vendor') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6" id="customname">
                                <label class="require form-label">{{ __('Name') }}</label>
                                <input class="form-control {{ !empty($errors->first('name')) ? 'is-invalid' : '' }}"
                                    type="text" name="name"  placeholder="{{ __('Name') }}">
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6 d-none" id="emailStaff">
                                <label class="require form-label">{{ __('Select') }}</label>
                                {{ Form::select('staff_name', $staff, null, ['class' => 'form-control select_person_email', 'id' => 'select', 'placeholder' => __('Select Staff')]) }}
                            </div>
                            <div class="form-group col-md-6 d-none" id="emailClient">
                                <label class="require form-label">{{ __('Select') }}</label>
                                {{ Form::select('client_name', $client, null, ['class' => 'form-control select_person_email', 'id' => 'select', 'placeholder' => __('Select Client')]) }}
                            </div>
                            <div class="form-group col-md-6 d-none" id="emailVendor">
                                <label class="require form-label">{{ __('Select') }}</label>
                                {{ Form::select('vendor_name', $vendor, null, ['class' => 'form-control select_person_email', 'id' => 'select', 'placeholder' => __('Select Vendor')]) }}
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Email') }}</label>
                                <input class="form-control {{ !empty($errors->first('email')) ? 'is-invalid' : '' }}"
                                    type="email" name="email" required="" placeholder="{{ __('Email') }}"
                                    id="emailAddressField">
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Category') }}</label>
                                <select class="form-control {{ !empty($errors->first('category')) ? 'is-invalid' : '' }}"
                                    name="category" required="" id="category">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    {{ $errors->first('category') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Status') }}</label>
                                <select class="form-control {{ !empty($errors->first('status')) ? 'is-invalid' : '' }}"
                                    name="status" required="" id="status">
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="In Progress">{{ __('In Progress') }}</option>
                                    <option value="On Hold">{{ __('On Hold') }}</option>
                                    <option value="Closed">{{ __('Closed') }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Subject') }}</label>
                                <input class="form-control {{ !empty($errors->first('subject')) ? 'is-invalid' : '' }}"
                                    type="text" name="subject" required="" placeholder="{{ __('Subject') }}">
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Attachments') }}
                                    <small>({{ __('You can select multiple files') }})</small> </label>
                                <div class="choose-file form-group">
                                    <label for="file" class="form-label d-block">

                                        <input type="file" name="attachments[]" id="file"
                                            class="form-control mb-2 {{ $errors->has('attachments') ? ' is-invalid' : '' }}"
                                            multiple="" data-filename="multiple_file_selection"
                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                        <img src="" id="blah" width="20%" />
                                        <div class="invalid-feedback">
                                            {{ $errors->first('attachments.*') }}
                                        </div>
                                    </label>
                                </div>
                                <p class="multiple_file_selection mx-4"></p>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="require form-label">{{ __('Description') }}</label>

                                    <textarea name="description"
                                    class="form-control summernote  {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" required
                                    id="description_ck"></textarea>

                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            </div>

                            @if (!$fields->isEmpty())
                                @include('supportticket::formBuilder')
                            @endif

                        </div>
                        <div class="d-flex justify-content-end text-end">
                            <a class="btn btn-secondary btn-light btn-submit"
                                href="{{ route('support-tickets.index') }}">{{ __('Cancel') }}</a>
                            <button class="btn btn-primary btn-submit ms-2" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>


    <script>
        var $emailAddressField = $('#emailAddressField');
        $('input[type=radio][name=account_type]').change(function() {
            if (this.value === 'staff') {
                $('#customname').addClass('d-none');
                $('#emailClient').addClass('d-none');
                $('#emailVendor').addClass('d-none');
                $('#emailStaff').removeClass('d-none');
                $emailAddressField.css('background-color', '#e9ecef');
                if ($(this).val() !== '') {
                    $emailAddressField.prop('readonly', true);
                } else {
                    $emailAddressField.prop('readonly', false);
                }
            } else if (this.value === 'client') {
                $('#customname').addClass('d-none');
                $('#emailStaff').addClass('d-none');
                $('#emailVendor').addClass('d-none');
                $('#emailClient').removeClass('d-none');
                $emailAddressField.css('background-color', '#e9ecef');
                if ($(this).val() !== '') {
                    $emailAddressField.prop('readonly', true);
                } else {
                    $emailAddressField.prop('readonly', false);
                }
            } else if (this.value === 'vendor') {
                $('#customname').addClass('d-none');
                $('#emailStaff').addClass('d-none');
                $('#emailClient').addClass('d-none');
                $('#emailVendor').removeClass('d-none');
                $emailAddressField.css('background-color', '#e9ecef');
                if ($(this).val() !== '') {
                    $emailAddressField.prop('readonly', true);
                } else {
                    $emailAddressField.prop('readonly', false);
                }
            } else if (this.value === 'custom') {
                $('#emailStaff').addClass('d-none');
                $('#emailClient').addClass('d-none');
                $('#emailVendor').addClass('d-none');
                $('#customname').removeClass('d-none');
                $('.person_email').val('');
                $emailAddressField.css('background-color', '');
                if ($(this).val() == '') {
                    $emailAddressField.prop('readonly', true);
                } else {
                    $emailAddressField.prop('readonly', false);
                }
            }
        });
    </script>

    <script>
        $(document).on('change', '.select_person_email', function() {
            var userId = $(this).val();
            $.ajax({
                url: '{{ route('support-tickets.getuser') }}',
                type: 'POST',
                data: {
                    "user_id": userId,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#emailAddressField').val(data.email);
                }
            });
        });
    </script>
@endpush
