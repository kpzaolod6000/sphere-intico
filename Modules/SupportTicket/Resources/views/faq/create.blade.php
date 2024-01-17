<form method="post" class="needs-validation" action="{{ route('support-ticket-faq.store') }}">
    @csrf
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn', [
                    'template_module' => 'faq',
                    'module' => 'SupportTicket',
                ])
            @endif
        </div>
        <div class="row">
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Title') }}</label>
                <div class="col-sm-12 col-md-12">
                    <input type="text" placeholder="{{ __('Title of the Faq') }}" name="title"
                        class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                        value="{{ old('title') }}" required="" autofocus>
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label class="form-label">{{ __('Description') }}</label>
                <div class="col-sm-12 col-md-12">
                    <textarea name="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                        placeholder="{{ __('Enter Description') }}" required="">{{ old('description') }}</textarea>
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-sm-12 col-md-12 text-end">
                <button class="btn btn-primary btn-block btn-submit"><span>{{ __('Add') }}</span></button>
            </div>
        </div>
    </div>
</form>


