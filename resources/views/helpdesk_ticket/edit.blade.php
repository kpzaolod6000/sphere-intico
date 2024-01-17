@extends('layouts.main')

@section('page-title')
    {{ __('Reply Ticket') }} - {{ $ticket->ticket_id }}
@endsection

@section('page-breadcrumb')
    {{ __('Tickets') }},{{ __('Reply') }}
@endsection

@section('page-action')
<div>
    @can('helpdesk ticket edit')
        @if(Auth::user()->id == $ticket->created_by || Auth::user()->type == 'super admin')
            <div class="btn btn-sm btn-info btn-icon m-1 float-end">
                <a href="#ticket-info" class="" type="button" data-bs-toggle="collapse" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ __('Edit Ticket') }}"><i class="ti ti-pencil text-white"></i></a>
            </div>
        @endif
    @endcan
</div>
@endsection

@section('content')
    @can('helpdesk ticket edit')
        {{ Form::model($ticket, ['route' => ['helpdesk.update', $ticket->id], 'id' => 'ticket-info', 'class' => 'collapse mt-3', 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6" id="customname">
                                @php
                                    $name = (\Auth::user()->type== 'company' || Auth::user()->id != $ticket->created_by) ? 'Admin' : 'Customers';
                                @endphp
                                <label class="require form-label">{{$name}}</label>
                                        <select  class="form-control select_person_email" name="name"  {{ !empty($errors->first('name')) ? 'is-invalid' : '' }} required="">
                                            @if(Auth::user()->id == $ticket->created_by && Auth::user()->type != 'company')
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
                                    type="email" name="email" id="emailAddressField" required="" value="{{ $ticket->email }}"
                                    placeholder="{{ __('Email') }}" readonly  style="background-color:#e9ecef ">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Category') }}</label>
                                <select class="form-select {{ !empty($errors->first('category')) ? 'is-invalid' : '' }}"
                                    name="category" required="">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @if ($ticket->category == $category->id) selected @endif>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('category'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('category') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Status') }}</label>
                                <select class="form-select {{ !empty($errors->first('status')) ? 'is-invalid' : '' }}"
                                    name="status" required="">
                                    <option value="In Progress" @if ($ticket->status == 'In Progress') selected @endif>
                                        {{ __('In Progress') }}</option>
                                    <option value="On Hold" @if ($ticket->status == 'On Hold') selected @endif>
                                        {{ __('On Hold') }}</option>
                                    <option value="Closed" @if ($ticket->status == 'Closed') selected @endif>
                                        {{ __('Closed') }}</option>
                                </select>
                                @if ($errors->has('status'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('status') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Subject') }}</label>
                                <input class="form-control {{ !empty($errors->first('subject')) ? 'is-invalid' : '' }}"
                                    type="text" name="subject" required="" value="{{ $ticket->subject }}"
                                    placeholder="{{ __('Subject') }}">
                                @if ($errors->has('subject'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('subject') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Attachments') }}
                                    <small>({{ __('You can select multiple files') }})</small> </label>
                                <div class="choose-file form-group">
                                    <label for="file" class="form-label d-block">


                                        <input type="file" name="attachments[]" id="file"
                                            class="form-control mb-2 {{ $errors->has('attachments') ? ' is-invalid' : '' }}"
                                            multiple="" data-filename="multiple_file_selection"
                                            onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">

                                        <div class="invalid-feedback">
                                            {{ $errors->first('attachments') }}
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-12 ">
                                <div class="mx-3">
                                    <p class="multiple_file_selection mb-0"></p>
                                    <div class="w-100 attachment_list row">
                                        @if (!empty($ticket->attachments))
                                            @php $attachments = json_decode($ticket->attachments); @endphp
                                            @foreach ($attachments as $index => $attachment)
                                                <div class="col-auto px-0 mt-2">
                                                    <a download="" href="{{ get_file($attachment->path) }}"
                                                        class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" title="{{ __('Download') }}"><i
                                                            class="ti ti-arrow-bar-to-down me-2"></i>
                                                        {{ $attachment->name }}</a>

                                                    <a class="bg-danger ms-2 mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        title="{{ __('Delete') }}"
                                                        onclick="(confirm('Are You Sure?')?(document.getElementById('user-form-{{ $index }}').submit()):'');"><i
                                                            class="ti ti-trash text-white"></i></a>
                                                </div>
                                            @endforeach
                                        @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12 mt-2">
                                <label class="require form-label">{{ __('Description') }}</label>
                                <textarea name="description"
                                    class="form-control ckdescription {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" id="description_ck">{!! $ticket->description !!}</textarea>
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('description') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="text-end">
                            <a class="btn btn-secondary btn-light mr-2"
                                href="{{ route('helpdesk.index') }}">{{ __('Cancel') }}</a>
                            <button class="btn btn-primary btn-block btn-submit" type="submit">{{ __('Update') }}</button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
        {{ Form::close() }}
        @if (!empty($ticket->attachments))
            @foreach ($attachments as $index => $attachment)
                <form method="post" id="user-form-{{ $index }}"
                    action="{{ route('helpdesk-ticket.attachment.destroy', [$ticket->id, $index]) }}">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endif
    @endcan
    <div class="row mt-3">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h6>
                            <span class="text-left">
                                {{ $ticket->name }} <small>({{ $ticket->created_at->diffForHumans() }})</small>
                                <span class="d-block"><small>{{ $ticket->email }}</small></span>
                            </span>
                        </h6>
                        <small>
                            <span class="text-right">
                                {{ __('Status') }} : <span
                                    class="badge rounded @if ($ticket->status == 'In Progress') badge bg-warning  @elseif($ticket->status == 'On Hold') badge bg-danger @else badge bg-success @endif">{{ __($ticket->status) }}</span>
                            </span>
                            <span class="d-block">
                                {{ __('Category') }} : <span
                                    class="badge bg-primary rounded">{{ $ticket->tcategory ? $ticket->tcategory->name : '-' }}</span>
                            </span>
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <p>{!! $ticket->description !!}</p>
                    </div>
                    @if (!empty($ticket->attachments))
                        @php $attachments = json_decode($ticket->attachments); @endphp
                        @if (count($attachments))
                            <div class="m-1">
                                <h6>{{ __('Attachments') }} :</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach ($attachments as $index => $attachment)
                                        <li class="list-group-item px-0">
                                            {{ $attachment->name }} <a download=""
                                                href="{{ get_file($attachment->path) }}" class="edit-icon py-1 ml-2"
                                                title="{{ __('Download') }}"><i class="fas fa-download ms-2"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @foreach ($ticket->conversions as $conversion)
                <div class="card">
                    <div class="card-header">
                        <h6>{{ $conversion->replyBy()->name }}
                            <small>({{ $conversion->created_at->diffForHumans() }})</small>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div>{!! $conversion->description !!}</div>
                        @php $attachments = json_decode($conversion->attachments); @endphp
                        @if (count($attachments))
                            <div class="m-1">
                                <h6>{{ __('Attachments') }} :</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach ($attachments as $index => $attachment)
                                        <li class="list-group-item px-0">
                                            {{ $attachment->name }}<a download=""
                                                href="{{ get_file($attachment->path) }}" class="edit-icon py-1 ml-2"
                                                title="{{ __('Download') }}"><i class="fa fa-download ms-2"></i></a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                @can('helpdesk ticket reply')
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>{{ __('Add Reply') }}
                                </h6>
                            </div>
                            <form method="post" id="ckeditorForm" action="{{ route('helpdesk-ticket.conversion.store', $ticket->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="require form-label">{{ __('Description') }}</label>
                                        <textarea name="reply_description" class="form-control ckdescription" id="reply_description"></textarea>
                                        <div class="invalid-feedback d-block ">
                                            {{ $errors->first('reply_description') }}
                                        </div>
                                    </div>

                                    <p class="text-danger d-none" id="skill_validation">{{__('Description filed is required.')}}</p>
                                    <div class="form-group file-group">
                                        <label class="require form-label">{{ __('Attachments') }}</label>
                                        <label
                                            class="form-label"><small>({{ __('You can select multiple files') }})</small></label>
                                        <div class="choose-file">
                                            <label for="file" class="form-label d-block">

                                                <input type="file" name="reply_attachments[]" id="file"
                                                    class="form-control mb-2 {{ $errors->has('reply_attachments') ? ' is-invalid' : '' }}"
                                                    multiple="" data-filename="multiple_reply_file_selection"
                                                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">

                                                <div class="invalid-feedback">
                                                    {{ $errors->first('reply_attachments.*') }}
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <p class="multiple_reply_file_selection"></p>
                                    <div class="text-end">
                                        <button class="btn btn-primary btn-block mt-2 btn-submit"
                                            type="submit" id="save">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>{{ __('Note') }}
                                </h6>
                            </div>
                            <form method="post" id="notesform" action="{{ route('helpdesk-ticket.note.store', $ticket->id) }}">
                                @csrf
                                <div class="card-body adjust_card_width">
                                    <div class="form-group ckfix_height">
                                        <textarea name="note" class="form-control ckdescription" id="note">{{ $ticket->note }}</textarea>

                                        <div class="invalid-feedback">
                                            {{ $errors->first('note') }}
                                        </div>
                                    </div>
                                    <p class="text-danger d-none" id="note_validation">{{__('Note filed is required.')}}</p>
                                    <div class="text-end">
                                        <button class="btn btn-primary btn-block mt-2 btn-submit"
                                            type="submit">{{ __('Add Note') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>

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

        $("#ckeditorForm").submit(function(e)
        {
            var desc = $("#ckeditorForm iframe").contents().find("body").text();
            if(!isNaN(desc))
            {
                $('#skill_validation').removeClass('d-none')
                event.preventDefault();
            }
            else
            {
                $('#skill_validation').addClass('d-none')
            }

        });
    </script>
    <script>
        $("#notesform").submit(function(e)
        {
            var desc = $("#notesform iframe").contents().find("body").text();
            if(!isNaN(desc))
            {
                $('#note_validation').removeClass('d-none')
                event.preventDefault();
            }
            else
            {
                $('#note_validation').addClass('d-none')
            }

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

@push('css')
    <style>
        .attachment_list li {
            list-style: none;
            display: inline;
        }
    </style>
@endpush
@push('scripts')
@endpush
