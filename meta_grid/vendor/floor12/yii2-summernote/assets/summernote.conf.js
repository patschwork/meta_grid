const summernoteParams = {
    fileField: null,
    fileClass: null,
    placeholder: 'Enter text here...',
    lang: 'ru-RU',
    imageTitle: {
        specificAltField: true,
    },
    codemirror: {
        theme: 'monokai',
    },
    height: 200,
    toolbar: [
        ['style', ['style']],
        ['style', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['link', ['link', 'picture']],
        ['insert', ['table']],
        ['misc', ['codeview', 'fullscreen']]
    ],
    popover: {
        image: [
            ['image', ['imageTitle', 'resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
            ['float', ['floatLeft', 'floatRight', 'floatNone']],
            ['remove', ['removeMedia']],
        ],
    },
    onCreateLink: function (originalLink) {
        return originalLink; // return original link
    },

    callbacks: {
        onImageUpload: function (files) {
            sendFile(files[0], summernoteParams.fileField, summernoteParams.fileClass);
        }
    }

}

function sendFile(file, fileField, fileClass) {
    data = new FormData();
    data.append('modelClass', fileClass);
    data.append('attribute', fileField);
    data.append('mode', 'multi');
    data.append('_fileFormToken', yii2FileFormToken);
    data.append("file", file);
    $.ajax({
        data: data,
        type: "POST",
        url: yii2UploadRoute,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            const fileBlock = $(response);
            const isImage = fileBlock.find('.floor12-file-object').hasClass('floor12-file-object-image');
            const filename = fileBlock.find('.floor12-file-object').data('filename');
            const title = fileBlock.find('.floor12-file-object').data('title');
            $(".floor12-files-widget-list[data-field='" + fileField + "']").append(fileBlock);
            if (isImage)
                document.execCommand('insertImage', false, filename);
            else
                document.execCommand('createLink', false, filename.toString());
        },
        error: function (response) {
            console.error(response);
            //document.execCommand('insertImage', false, url);
        }
    });
}
