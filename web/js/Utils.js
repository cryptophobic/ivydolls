function Utils() {

}

Utils.prototype = {
    constructor: Utils,
    isElementInView: function (element, fullyInView) {
        var pageTop = $(window).scrollTop();
        var pageBottom = pageTop + $(window).height();

        if(element instanceof jQuery  === false)
        {
            element = $(element);
        }

        var elementTop = element.offset().top;
        var elementBottom = elementTop + element.height();

        if (fullyInView === true) {
            return ((pageTop < elementTop) && (pageBottom > elementBottom));
        } else {
            return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
        }
    },
    loadTo: function (selector, url) {
        $.ajax({
            url: url,
            dataType: "json",
            beforeSend: function (xhr)
            {
                $(selector).html('<div class="loader"></div>');
            },
            success: function(data) {
                $(selector).html(data.body);
            }
        })
    }
};

$(document).ready(function() {
    //$('#myModal').modal('show');
})