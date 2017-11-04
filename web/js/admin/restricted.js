$(document).ready(function(){
    var restricted = $('.restricted');
    restricted.click(function(){
        let jQueryObject = $(this);
        let container = jQueryObject.data('container');
        $('.'+container).toggle();
    });

    restricted.trigger('click');

});
