/**
 * Created by dima on 3/22/2017.
 */
function cartItem(id)
{
    var price = $('#'+id+'total');
    var select = $('#'+id+'weight');
    var name = $('#'+id+'name');
    var image = $('#'+id+'image');
    var multiplier = select.val();

    if (price.length > 0)
    {
        var initial = price.attr('data-initial');
        if (multiplier > 1)
        {
            initial = initial * 9 / 10;
        }
        var total = initial*multiplier;
        price.attr('data-total', total);
    }

    this.total = price.attr('data-total');
    this.price = price.attr('data-initial');
    this.weight = select.attr('data-initial') * multiplier;
    this.image = image.attr('src');
    this.name = name.html();
    this.quantity = 1;
    this.id = id;
}