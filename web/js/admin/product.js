$(document).ready(function() {
    $('input[type="checkbox"].checkOption').click(function () {
        let elem = $(this);

        let id = elem.data("id");

        if (this.checked)
        {
            $('#' + id).prop('disabled', false);
        } else {
            $('#' + id).prop('disabled', true);
        }
    })

    let timerId = undefined;

    $('.suggestions').on('input', function() {
        if(timerId !== undefined)
        {
            clearTimeout(timerId);
        }

        let el = $(this);

        timerId = setTimeout(function () {
            return suggest(el)
        }, 500);
    });
    
    function suggest(el) {
        $.ajax({
            url: '/start/load-products-json',
            data: {keyword: el.val()},
            dataType: "json",
            beforeSend: function (xhr)
            {
                //$('#content-container').html('<div class="loader"></div>');
            },
            success: function(data) {
                console.log(data);
                $('#suggest-container').html(data.body);
            }
        })
    }

    $('a.activate').click(function () {
        let element = $(this);
        let productId = element.data('product_id');
        let productsImageId = element.data('products_image_id');
        let action = element.data('action');
        $.ajax({
            url: action,
            method: "get",
            data: {
                productId: productId,
                productsImageId: productsImageId
            },
            dataType: "json",
            success: function(data) {
                $('.galleryAdmin').removeClass('framed');
                if(data.main !== undefined)
                {
                    $('#gallery'+data.main).addClass('framed');
                }
            }




        })
    })
    CKEDITOR.replace( 'description' );
});