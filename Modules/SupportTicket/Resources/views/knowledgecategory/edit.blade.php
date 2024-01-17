<form method="post" action="{{ route('knowledge-category.update', $knowledge_category->id) }}">
    @csrf
    @method('PUT')
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
            <label class="form-label">{{ __('Title') }}</label>
            <div class="col-sm-12 col-md-12">
                <input type="text" placeholder="{{ __('Title of the Knowledge') }}" name="title"
                    class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                    value="{{ $knowledge_category->title }}" required="" autofocus>
                <div class="invalid-feedback">
                    {{ $errors->first('title') }}
                </div>
            </div>
            <div class="col-sm-12 col-md-12 text-end mt-3">
                <button class="btn btn-primary btn-block btn-submit"><span>{{ __('Update') }}</span></button>
            </div>
        </div>
    </div>
</form>
