@extends('layouts.main')

@section('page-title')
    {{ __('Create Ticket') }}
@endsection

@section('page-breadcrumb')
    {{ __('Tickets') }},{{ __('Create') }}
@endsection

@section('content')
    <form action="{{ route('helpdesk.store') }}" class="mt-3" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @php
                                $name = (\Auth::user()->type== 'company') ? 'Admin' : 'Customers';
                            @endphp
                            <div class="form-group col-md-6" id="customname">
                                <label class="require form-label">{{ $name}}</label>
                                <select  class="form-control select_person_email" name="name"  {{ !empty($errors->first('name')) ? 'is-invalid' : '' }} required="">
                                    @if(Auth::user()->type != 'company')
                                        <option value="">{{ __('Select User') }}</option>
                                        @foreach ($users as $key=>$value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ $users->id }}">{{ $users->name }}</option>
                                    @endif
                                    </select>

                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Email') }}</label>
                                <input class="form-control emailAddressField {{ !empty($errors->first('email')) ? 'is-invalid' : '' }}"
                                    type="email" name="email" required="" placeholder="{{ __('Email') }}" @if(Auth::user()->type == 'company') value="{{$users->email}}" readonly  style="background-color:#e9ecef " @endif>
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
                                    class="form-control ckdescription  {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" required
                                    id="description_ck"></textarea>
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-end text-end">
                            <a class="btn btn-secondary btn-light btn-submit"
                                href="{{ route('helpdesk.index') }}">{{ __('Cancel') }}</a>
                            <button class="btn btn-primary btn-submit ms-2" type="submit">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="//cdn.ckeditor.com/4.12.1/basic/ckeditor.js"></script>
    <script src="{{ asset('js/editorplaceholder.js') }}"></script>

    <script>
        $(document).ready(function() {
            $.each($('.ckdescription'), function(i, editor) {
                CKEDITOR.replace(editor, {
                    // contentsLangDirection: 'rtl',
                    extraPlugins: 'editorplaceholder',
                    editorplaceholder: editor.placeholder
                });
            });
        });
    </script>

    <script>

        $(document).on('change', '.select_person_email', function() {
            var userId = $(this).val();
            $.ajax({
                url: '{{ route('helpdesk-tickets.getuser') }}',
                type: 'POST',
                data: {
                    "user_id": userId,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    if(data.email)
                    {
                        $('.emailAddressField').val(data.email);
                        $('.emailAddressField').prop('readonly', true);
                        $('.emailAddressField').css('background-color', '#e9ecef');
                    }else{
                        $('.emailAddressField').val('');
                        $('.emailAddressField').prop('readonly', false);
                        $('.emailAddressField').css('background-color', '');
                    }
                }
            });
        });
    </script>
@endpush
