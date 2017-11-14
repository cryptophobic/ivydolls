content = {
    load: function(url) {
        $.ajax({
            url: url,
            dataType: "json",
            beforeSend: function (xhr)
            {
                $('#content-container').html('<div class="loader"></div>');
            },
            success: function(data) {
                window.history.pushState('page2', 'Title', url);
                $('#content-container').html(data.body);
            }
        })
    },
    append: function(url) {
        $.ajax({
            url: url,
            dataType: "json",
            beforeSend: function (xhr)
            {
                $('#content-container').append('<div class="loader"></div>');
            },
            success: function(data) {
                $('.loader').remove();
                $('#content-container').append(data.body);
            }
        })
    }
}

$(document).ready(function(){
    $('.menu-item').click(function(){
        let jQueryObject = $(this);
        $('.menu-item.active').each(function(){$(this).removeClass('active')})
        jQueryObject.addClass('active');
    });

    $('#selectAll').click(function() {
        $("input:checkbox").prop('checked', $("#selectAll").prop("checked"));
    });

    $(document).on('click', 'input:checkbox.batch', function() {
        let checkbox = $(this);
        let target = checkbox.data('target');
        $('input:checkbox.'+target).prop('checked', checkbox.prop("checked"));
    });


    $('.loadable').click(function() {
        var url = $(this).parent().data('url');
        window.location = url;
    });

    function prepareSubmit (obj){
        let button = $(obj);
        let action = button.data('action');
        let form = button.parents('form');
        if (action !== undefined)
        {
            form.attr('action', action);
        }

        let method = button.data('method');
        if (method !== undefined)
        {
            form.attr('method', method);
        }

        if (typeof CKEDITOR !== 'undefined') {
            let description = $('#inputDescription');
            description.val(CKEDITOR.instances.inputDescription.getData());
        }
        return form;
    }


    $(".submitiative").change(function() {
        let form =  prepareSubmit(this);
        form.submit();
    });

    $("#filter1,#filter2").change(function()
    {
        let el = $(this);
        if (el.val() !== 0)
        {
            if (el.attr('id') === 'filter1') {
                $("#filter2").val("0").trigger('change');
            }
            $("#filter3").val("0").trigger('change');
        }
    });


    $("button[type=submit]").click(function() {
        return prepareSubmit(this);
    });
    
    $(".required").focus(function () {
        $(this).removeClass('alert-danger');
    })

    $('form.submitWarn').submit(function(event){
        event.stopImmediatePropagation();

        let action = $(this).attr('action');

        let inputs = $('input:text.required, select.required option:selected[value=""]')
            .filter(function() { return $(this).val() === ""; });

        if (inputs.length > 0)
        {
            inputs.addClass('alert-danger');
            alert("Заполните обязательные поля");
        } else if (!action.toString().includes('delete') || confirm('Пожалуйста подтвердите удаление')) {
            return true;
        }
        event.preventDefault();
        return false;

    });
});
