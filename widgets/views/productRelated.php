<?php
/**
 * @var ProductPresentation $productPresentation
 */

use app\models\Category\CategoryPack;
use app\models\Products\ProductPresentation;

$relatedCategories = $productPresentation->getRelatedCategories();
$product = $productPresentation->getProduct();

$related = $product->products_related;
$i = 0;
if (count($relatedCategories) > 0) {
    ?>
    <table id="inputRelated" class="table table-hover">
        <?php

        foreach ($relatedCategories as $categoryId => $relatedCategory) {
            /** @var CategoryPack $category */
            $category = $relatedCategory['category'];
            /** @var array $relatedKeys */
            $relatedKeys = $relatedCategory['relatedKeys'];
            ?>
            <thead>
            <tr>
                <th colspan="4"><?= $category->name ?><? if ($category->category_id === $product->category_id) {?> (альтернативный вариант товара)<?}?></th>
            </tr>
            </thead>

            <tbody>
            <?php
            foreach ($relatedKeys as $keys) {
                $i++;
                $related->moveToItem($keys);
                ?>
                <tr class="checkRow">
                    <td>
                        <input id="relCheck<?= $related->product_related_id ?>"
                               data-price="<?= $related->price ?>"
                               class="priceCollector checkRelated<?= $category->category_id ?>"
                               type="checkbox"
                               name="relatedInclude[]"
                               value="<?= $related->product_related_id ?>">
                    </td>
                    <td>
                        <label for="relCheck<?= $related->product_related_id ?>"><img src="<?= $related->products->products_images->image_thumb ?>"></label>
                    </td>

                    <td>
                        <a target="_blank" href="/dolls/?productId=<?= urlencode($related->products->product_id) ?>"><?= $related->products->name ?></a>
                    </td>
                    <td class="text-right">
                        <span><?= $related->price ?></span>
                    </td>
                </tr>

                <?php
            }
            ?>
            </tbody>
            <?php
        }
        ?>
    </table>
    <?php
}
?>
