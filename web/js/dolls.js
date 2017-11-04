$(document).ready(function() {

    let initialPrice = parseFloat($('input[name="initialPrice"]').val());
    let calculatedPrice = initialPrice;

    $('#carousel').carousel({
        interval: false
    });

    $(".owl-carousel").each(function () {
        const el = $(this);
        const prev = el.parent().find(".prev");
        const next = el.parent().find(".next");
        var Owlfn = el.owlCarousel({
            autoPlay: 10000, //Set AutoPlay to 3 seconds
            items: el.attr('data-items') || 1,
            loop: false,
            afterAction: function () {
                prev.removeClass('disabled');
                next.removeClass('disabled');
                if (this.currentItem == 0) {
                    prev.addClass('disabled');
                }
                if (this.currentItem == this.maximumItem) {
                    next.addClass('disabled');
                }
            }
        });
        next.click(function (e) {
            e.preventDefault();
            if (this.classList.contains('disabled'))   return;
            Owlfn.trigger('next.owl.carousel');
        });
        prev.click(function (e) {
            e.preventDefault();
            if (this.classList.contains('disabled'))   return;
            Owlfn.trigger('prev.owl.carousel');
        });
        //view full gallery

    });

    function recalculatePrices() {
        let finalDeltaPrice = 0;
        $('input:checkbox.priceCollector').each(function(){
            let thisJQuery = $(this);
            let price = parseFloat(thisJQuery.data('price'));
            if (thisJQuery.prop("checked") && price)
            {
                finalDeltaPrice += price;
            }
        });

        $('select.priceCollector').each(function(){
            let price = parseFloat($("option:selected", this).data('price'));
            if (price > 0)
            {
                finalDeltaPrice += price;
            }
        });
        calculatedPrice = initialPrice + finalDeltaPrice;
        $('input[name="calculatedPrice"]').val(calculatedPrice);
        $('#priceContainer').html(calculatedPrice);

    }
    
    $('select.priceCollector, input:checkbox.priceCollector').change(function () {
        recalculatePrices();
    })

    recalculatePrices();

    $('form.ajaxForm').submit(function(event){
        event.stopImmediatePropagation();
        let form = $(this);
        let action = form.attr('action');

        $.ajax({
            type: "POST",
            url: action,
            data: form.serialize(),
            beforeSend: function () {
                form.find('.warnMessage').hide();
                form.find('input, button, textarea').each(function() {$(this).prop('disabled', true)})
                form.children()
            },
            success: function (data, textStatus, jqXHR) {
                if (data.message)
                {
                    form.find('.warnMessage').html(data.message).show();
                    form.find('input, button, textarea').each(function() {$(this).prop('disabled', false)})
                } else {
                    form.html(data.body);
                }
            },
            complete: function () {
                scrollTo('#feedbackAnchor');
            },
            dataType: "json"
        });

        event.preventDefault();
        return false;

    });

});