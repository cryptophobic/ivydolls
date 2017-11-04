$(document).ready(function(){
    /*var assortment = $('#assortment');
    var sideBar = $('#sidebar');
    var assortmentPosition = assortment.offset();
    console.log(assortmentPosition.top);
    $('#sidebar').affix({offset: {top: assortmentPosition.top} });*/

    $('.sidebar-items').click(function(event){
        event.preventDefault();
        items.load($(this).attr('href'));
    })
});

