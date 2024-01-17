@if ($fields)
    @foreach ($fields as $customField)
        @if ($customField->custom_id == '1')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    <label for="name" class="form-label">{{ __($customField->name) }}</label>
                    <div class="form-icon-user">
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                            id="name" name="name" placeholder="{{ __($customField->placeholder) }}" required=""
                            value="{{ old('name') }}">
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('name') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif($customField->custom_id == '2')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">{{ __($customField->name) }}</label>
                    <div class="form-icon-user">
                        <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            id="email" name="email" placeholder="{{ __($customField->placeholder) }}"
                            required="" value="{{ old('email') }}">
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('email') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif($customField->custom_id == '3')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    <label for="category" class="form-label">{{ __($customField->name) }}</label>
                    <select class="form-select" id="category" name="category"
                        data-placeholder="{{ __($customField->placeholder) }}">
                        <option value="">{{ __($customField->placeholder) }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if (old('category') == $category->id) selected @endif>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('category') }}
                    </div>
                </div>
            </div>
        @elseif($customField->custom_id == '4')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    <label for="subject" class="form-label">{{ __($customField->name) }}</label>
                    <div class="form-icon-user">
                        <input type="text" class="form-control {{ $errors->has('subject') ? ' is-invalid' : '' }}"
                            id="subject" name="subject" placeholder="{{ __($customField->placeholder) }}"
                            required="" value="{{ old('subject') }}">
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('subject') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif($customField->custom_id == '5')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" class="form-control summernote {{ $errors->has('description') ? ' is-invalid' : '' }}"
                        placeholder="{{ __($customField->placeholder) }}" required="">{{ old('description') }}</textarea>

                        {{-- <textarea name="description"
                        class="form-control summernote  {{ !empty($errors->first('description')) ? 'is-invalid' : '' }}" placeholder="{{ __($customField->placeholder) }}" required
                        id="help-desc">{{ old('description') }}</textarea> --}}

                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                </div>
            </div>
        @elseif($customField->custom_id == '6')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    <label class="form-label">{{ $customField->name }}
                        <small>({{ $customField->placeholder }})</small></label>
                    <div class="choose-file form-group">
                        <label for="file" class="form-label">
                            <input type="file"
                                class="form-control {{ $errors->has('attachments.') ? 'is-invalid' : '' }}"
                                multiple="" name="attachments[]" id="file"
                                data-filename="multiple_file_selection">
                        </label>
                        <p class="multiple_file_selection"></p>
                    </div>
                </div>
            </div>
            <div class="invalid-feedback d-block">
                {{ $errors->first('attachments.*') }}
            </div>
        @elseif($customField->type == 'text')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3{{ $customField->width }}">
                    {{ Form::label('fields-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::text('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::text('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'email')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    {{ Form::label('fields-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::email('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::email('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'number')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    {{ Form::label('fields-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::number('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::number('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'date')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    {{ Form::label('fields-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::date('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::date('fields[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'textarea')
            <div class="col-md-{{ $customField->width }}">
                <div class="form-group mb-3">
                    {{ Form::label('fields-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::textarea('fields[' . $customField->id . ']', null, ['class' => 'form-control  pc-tinymce-2', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::textarea('fields[' . $customField->id . ']', null, ['class' => 'form-control  pc-tinymce-2', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @endif
    @endforeach
@endif
