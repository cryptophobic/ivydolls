$(document).ready(function() {
    let jQuerySelector = $("#input-id");

    let form = $('form#upload');

    let csrf = $('meta[name="csrf-token"]').attr("content");

    let uploadUrl = form.attr('action');

    let values = {};

    $.each(form.serializeArray(), function(i, field) {
        values[field.name] = field.value;
    });


    let options = {
        uploadAsync: true,
        showPreview: false,
        previewFileType: 'any',
        showUpload: true,
        elErrorContainer: '#errorContainer',
        allowedFileExtensions: ["jpg"],
        uploadUrl: uploadUrl,
        resizeImage: true,
        maxImageWidth: 700,
        maxImageHeight: 1050,
        resizePreference: 'width',

        uploadExtraData: function() {
            return values;
        }

    };
    jQuerySelector.fileinput(options);

    jQuerySelector.on('filebatchuploadcomplete', function(event, data, previewId, index) {
        /*var form = data.form, files = data.files, extra = data.extra,
            response = data.response, reader = data.reader;*/
        location.reload();
    });


    //jQuerySelector.fileinput({'showUpload': false, 'previewFileType': 'any'});

});