<form method="post" action="{{ route('support-ticket-knowledge.update', $knowledge->id) }}">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn', [
                    'template_module' => 'knowledge',
                    'module' => 'SupportTicket',
                ])
            @endif
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Title') }}</label>
                <div class="col-sm-12 col-md-12">
                    <input type="text" placeholder="{{ __('Title of the Knowledge') }}" name="title"
                        class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                        value="{{ $knowledge->title }}" required="" autofocus>
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Category') }}</label>
                <div class="col-sm-12 col-md-12">
                    <select class="form-select" name="category">
                        @foreach ($category as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $knowledge->category == $cat->id ? 'selected' : '' }}>
                                {{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Description') }}</label>
                <div class="col-sm-12 col-md-12">
                    <textarea name="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }} summernote "
                        required="">{{ $knowledge->description }}</textarea>
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-label"></label>
                <div class="col-sm-12 col-md-12 text-end">
                    <button class="btn btn-primary btn-block btn-submit"><span>{{ __('Update') }}</span></button>
                </div>
            </div>
        </div>
    </div>
</form>
