/**
 * Created by dima on 3/19/2017.
 */

let utils = new Utils();

$(document).ready( function() {
    $('.carousel').carousel({
        interval: 1000 * 10
    });

    $('.scroll-link').click(function(event)
    {
        event.preventDefault();
        if (this.hash !== "") {
            scrollTo(this.hash);
        }
    });

    $(document).on('click', '.modal-load', function () {
        let action = $(this).data('content');
        utils.loadTo('.modal-body', action);
    })
});

let scrollTo = function(hash)
{
    let scroll = $(hash).offset().top - 55;
    $('html, body').animate({
        scrollTop: scroll
    }, 800, function(){
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
    });
}

