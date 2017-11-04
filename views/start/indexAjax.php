<?php

use app\models\Products\ProductsCollection;
use app\models\Products\ProductsPack;

/** @var ProductsPack $items */

for ($items->first();$items->current();$items->next())
{
    ?>
    <div class="col-sm-3">
        <div class="thumbnail">
            <a href="/dolls/?productId=<?= $items->product_id ?>"><img id="<?= $items->product_id ?>image" data-id="<?= $items->product_id ?>" class="img-responsive" src="<?= $items->products_images->first() == true?$items->products_images->image_low:''; ?>" alt="<?= $items->name ?>"/>
            <div class="caption">
                <h4 id="<?= $items->product_id ?>name" data-id="<?= $items->product_id ?>"
                    class="group inner list-group-item-heading">
                    <a href="/dolls/?productId=<?= $items->product_id ?>"><?= substr($items->name, 0, 20) ?></a>
                </h4>

                <table class="table productSummary">
                    <tbody>
                    <tr>
                        <td align="right">$<?= $items->price ?></td>
                    </tr>
                    <tr>
                        <td>
                            <a class="iconLow" href="#" title="Добавить в любимые"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            <!--<a class="iconLow" href="#" title="Сравнить"><i class="fa fa-retweet" aria-hidden="true"></i></a>-->
                        </td>
                        <td align="right">
                            <a class="iconLow" href="#" title="Добавить в корзину"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            <!--<a class="iconLow" href="#" title="Детали"><i class="fa fa-info" aria-hidden="true"></i></a>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
}
?>
