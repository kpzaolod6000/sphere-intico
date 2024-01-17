import { generateKey, srvSave } from '../diagram/dgrm-srv.js';

$(document).on('click', '#save-and-build', function (e) {
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
            url: 'http://localhost/product/workdo-dash/internalknowledge/article/mindmap/update',
            method: 'PUT',
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            context: this,
            success: function (response) {
                const currentUrl = window.location.href;
                // var newUrl = response.newurl;
                const key = generateKey();
                var articleId = response.article_id;
                var contentData = response.content;
                // var newUrl = currentUrl + '/mindmap/' + articleId;
                const getApi = 'http://localhost/product/workdo-dash/internalknowledge/getmindmap/' + articleId + '/' + key;
                window.location.href = getApi;
                // const newUrl = window.location.href + '/?k=' + key;
                // console.log(newUrl);
                // var key = newUrl.substring(url.lastIndexOf('/') + 1);
                // console.log(key);

            },
            error: function (error) {
                console.error(error);
            }
        });
    } else {
        console.error('articleId is missing or undefined.');
    }
});
