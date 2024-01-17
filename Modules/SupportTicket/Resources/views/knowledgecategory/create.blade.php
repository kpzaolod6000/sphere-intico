<form method="post" class="needs-validation" action="{{ route('knowledge-category.store') }}">
    @csrf
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn', [
                    'template_module' => 'knowledge_category',
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
                        value="{{ old('title') }}" required="" autofocus>
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 text-end mt-3">
            <button class="btn btn-primary btn-block btn-submit"><span>{{ __('Add') }}</span></button>
        </div>

    </div>
</form>
