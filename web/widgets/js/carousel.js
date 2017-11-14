$(document).ready( function() {

    $('.carousel').carousel({
        interval: 1000 * 10
    });

    $('.carousel-inner .carousel-item').click(function () {
        let obj = $(this);
        let img = obj.children('img');
        let url = '';
        if (url = img.data('load'))
        {
            items.load(url);
            scrollTo('#assortment');
        } if (url = img.data('href'))
        {
            window.location.href = url;
        }
    })

});