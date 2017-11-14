/**
 * Created by dima on 6/5/2017.
 */

items = {
    fixUrl: function(url)
    {
        let prefix = '?';
        if(url.indexOf("?") >= 0)
        {
            prefix = '&';
        }
        url += prefix + 'rand='+Math.random();
        let manUrl = '/start?scrollUrl='+encodeURIComponent(url);
        //window.history.pushState(null, null, manUrl);
        return url;
    },
    load: function(url) {
        if (!url)
        {
            url = '/start/load-products/';
        }

        url = items.fixUrl(url);

        $.ajax({
            url: url,
            dataType: "json",
            beforeSend: function (xhr)
            {
                $('#content-container').html('<div class="loader"></div>');
            },
            success: function(data) {
                $('input[name="scrollUrl"]').attr('value', data.scrollUrl);
                $('#content-container').html(data.body);
            }
        })
    },
    append: function(url) {
        url = items.fixUrl(url);
        $.ajax({
            url: url,
            dataType: "json",
            beforeSend: function (xhr)
            {
                $('#content-container').append('<div class="loader"></div>');
            },
            success: function(data) {
                $('.loader').remove();
                $('input[name="scrollUrl"]').attr('value', data.scrollUrl);
                $('#content-container').append(data.body);
            }
        })
    }
}

$(document).ready( function() {

    if ($('#content-container').children().length === 0) {
        let scrollEl = $('input[name="scrollUrl"]');
        let url = scrollEl.attr('value');
        if (url) {
            scrollTo('#assortment');
        }
        items.load(url);
    }

    $('.navbar-form').on('submit', (function (eventData, handler) {
        eventData.preventDefault();

        let form = $(this);

        let inputs = form.serialize();
        let url = form.attr('action');
        items.load(url + '?' + inputs);
        scrollTo('#assortment');
    }));

    $(window).scroll(function () {
        clearTimeout($.data(this, 'scrollTimer'));
        $.data(this, 'scrollTimer', setTimeout(function () {
            let el = $('#content-container').first();
            if (utils.isElementInView(el)) {
                if ($(window).scrollTop() + $(window).height() === $(document).height()) {
                    let scrollEl = $('input[name="scrollUrl"]');
                    let url = scrollEl.attr('value');
                    if (url) {
                        $('input[name="scrollUrl"]').attr('value', '');
                        items.append(url);
                    }
                }

                console.log("Haven't scrolled in 250ms!");
            }
        }, 250));
    });
});