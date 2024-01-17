{{ Form::open(['route' => ['article.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('book', __('Book'), ['class' => 'form-label']) }}
            <select class="form-control select book" required="required" id="book" name="book">
                <option value="0">{{ __('Select Book') }}</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}">{{ $book->title }} </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
            {{ Form::text('title', '', ['class' => 'form-control title', 'required' => 'required', 'placeholder' => 'Enter Title']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', '', ['class' => 'form-control description', 'rows' => '3', 'placeholder' => 'Enter description']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
            <select class="form-control select type" required="required" id="type" name="type">
                <option value="document">{{ __('Document') }}</option>
                <option value="mindmap">{{ __('Mindmap') }}</option>
            </select>
        </div>
        <div class="form-group col-md-12" id="editor-container">
            <label for="editor">{{ __('Content') }}</label>
            <textarea class="pc-tinymce-2" id="editor" rows="2" name="content"></textarea>
        </div>
        <div class="form-group col-md-12 article_list" id="mindmap-container">
            <a href="{{ route('mindmap') }}">
                <button type="button" value="SAVE_AND_BUILD" class="btn btn-success"
                    id="save-and-build">{{ 'Save & Build Map' }}</button>
            </a>
            <input type="hidden" name="article_url" value="{{(url('/'))}}">
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>

{{ Form::close() }}
@push('scripts')
    {{-- <script src="{{ asset('Modules/Internalknowledge/Resources/assets/js/summernote-bs4.js') }}"></script> --}}
    {{-- <script>
        if ($(".pc-tinymce-2").length) {

            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script> --}}
@endpush
<script>
    $(document).ready(function() {
        changetype();
    });
    document.querySelector('#type').addEventListener('change', function() {
        changetype();

    });

    function changetype() {
        if ($("#type").val() == "document") {
            $('#mindmap-container').addClass('d-none');
            $('#editor-container').removeClass('d-none');

        } else {
            $('#mindmap-container').removeClass('d-none');
            $('#editor-container').addClass('d-none');
        }
    }
</script>

<script>
    $(document).on('click', '#save-and-build', function(e) {
        e.preventDefault(); // Prevent the default behavior (navigating to a new page)

        var book = $('.book').find(":selected").val();
        var title = $('.title').val();
        var description = $('.description').val();
        var type = $('.type').val();

        var data = {
            book: book,
            title: title,
            description: description,
            type: type,
        };

        $.ajax({
            url: '{{ route('mindmap') }}',
            method: 'GET',
            data: data,
            context: this,
            success: function(response) {

                const currentUrl = window.location.href;
                var articleId = response.article_id;
                var newUrl = currentUrl + '/mindmap/' + articleId;

                window.location.href = newUrl;
            }
        });
    });
</script>
