{{ Form::model($article, ['route' => ['article.update', $article->id], 'method' => 'put']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('book', __('Book'), ['class' => 'form-label']) }}
            <select class="form-control select book" required="required" id="book" name="book">
                <option value="0">{{ __('Select Book') }}</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}" @if ($book->id == $article->book) selected @endif>
                        {{ $book->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control title', 'required' => 'required', 'id' => 'title', 'placeholder' => 'Enter Title']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control description', 'rows' => '3', 'id' => 'description', 'placeholder' => 'Enter description']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
            <select class="form-control select type" required="required" id="type" name="type">
                <option value="document" {{ $article->type == 'document' ? 'selected' : '' }}>
                    {{ __('Document') }}
                </option>
                <option value="mindmap" {{ $article->type == 'mindmap' ? 'selected' : '' }}>
                    {{ __('Mindmap') }}
                </option>
            </select>
        </div>
        <div class="form-group col-md-12" id="editor-container">
            <label for="editor">{{ __('Content') }}</label>
            <textarea class="pc-tinymce-2" id="editor" rows="2" name="content">{{ $article->content }}</textarea>
        </div>
        <div class="form-group col-md-12 article_list" id="mindmap-container" name="content">
            <a href="{{ route('mindmap.update') }}">
                <button type="button" value="SAVE_AND_BUILD" class="btn btn-success" id="save-and-build">
                    {{ 'Save & Build Map' }}
                </button>
                <input type="hidden" id="articleId" value="{{ $article->id }}">
                <input type="hidden" name="article_url" value="{{ url('/') }}">
            </a>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>

{{ Form::close() }}
@push('scripts')
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
    function generateKey() {
        const arr = new Uint8Array((8 / 2));
        window.crypto.getRandomValues(arr);
        const date = new Date();
        return `${date.getUTCFullYear()}${(date.getUTCMonth() + 1).toString().padStart(2, '0')}${Array.from(arr, dec => dec.toString(16).padStart(2, '0')).join('')}`;
    }

    $(document).on('click', '#save-and-build', function(e) {
        e.preventDefault();
        var articleId = $('#articleId').val();
        var book = $('#book').find(":selected").val();
        var title = $('#title').val();
        var description = $('#description').val();
        var type = $('#type').val();

        var data = {
            id: articleId,
            book: book,
            title: title,
            description: description,
            type: type,
        };

        if (articleId) {
            $.ajax({
                url: '{{ route('mindmap.update') }}',
                method: 'PUT',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: 'json',
                context: this,
                success: function(response) {
                    const currentUrl = window.location.href;
                    const key = generateKey();
                    const textBox = document.getElementsByName("article_url")[0];
                    console.log(textBox);
                    const textBoxValue = textBox.value;
                    var articleId = response.article_id;
                    var contentData = response.content;
                    const getApi =
                        textBoxValue + '/internalknowledge/article/mindmap/' +
                        articleId + '/?k=' + key;
                    window.location.href = getApi;
                },
                error: function(error) {
                    console.error(error);
                }
            });
        } else {
            console.error('articleId is missing or undefined.');
        }
    });
</script>
