$(document).ready(function() {
    let draggable = $('[draggable="true"]');
    draggable.on('dragstart', function (e) {
        let event = e.originalEvent;
        console.log(event);
        event.dataTransfer.setData('action', $(event.target).data('action'));
    });

    draggable.on('dragover', function (e) {
        let event = e.originalEvent;
        event.preventDefault();
    });

    draggable.on('drop', function (e) {
        let event = e.originalEvent;
        let action = event.dataTransfer.getData('action');
        let no = $(this).data('no');
        let url = action + '&' + encodeQueryData({"no":no});

        $.ajax({
            url: url,
            dataType: "json",
            beforeSend: function (xhr)
            {
                $('#overlay').show();
            },
            complete: function(data) {
                window.location.reload();
            }
        })

        //alert();
    });



    function encodeQueryData(data) {
        let ret = [];
        for (let d in data) {
            ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
        }
        return ret.join('&');
    }

});