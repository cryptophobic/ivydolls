<?php

/** @var \app\models\Products\ProductsPack $products */
?>

<table class="col-sm-12">
    <thead>
    <tr>
        <th>
            <input type="checkbox" id="suggestCheck" data-target="suggestCheck" class="batch">
            <label for="suggestCheck">Выбр</label>
        </th>
        <th>Изобр</th>
        <th>Название</th>
        <th class="text-right">Цена</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $i = 0;
    for ($products->first();$products->current();$products->next(),$i++)
    {
        ?>
        <tr>
            <td>
                <input class="suggestCheck" type="checkbox" name="products_related[<?= $i ?>][product_related_id]" value="<?= $products->product_id ?>">
                <input type="hidden" name="products_related[<?= $i ?>][price]" value="<?= $products->price ?>">
            </td>

            <td>
                <img src="<?= $products->products_images->image_thumb ?>">
            </td>
            <td>
                <?= $products->name." (".$products->part_number.")" ?>
            </td>
            <td class="text-right">
                <?= $products->price ?>
            </td>
        </tr>
        <?
    }
    ?>
    </tbody>
</table>