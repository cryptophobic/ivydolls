<?php

/**
 * @var ProductPresentation $productPresentation
 * @var CategoryPack $categories
 */

use app\models\Category\CategoryPack;
use app\models\Products\ProductPresentation;

$product = $productPresentation->getProduct();
$extraSpecs = $productPresentation->getExtraSpecs();
$extraProductOptions = $productPresentation->getExtraProductOptions();
$extraOptions = $productPresentation->getExtraOptions();

$categories->moveToItem(['category_id' => $extraOptions->category_id]);

?>
<div class="container-fluid">
    <p class="text-danger">Продукт "<?= $product->name ?>" содержит характеристики/опции других категорий (<a target="_blank" href="/admin/category/category?categoryId=<?= $categories->category_id ?>"><?= $categories->name ?></a>).
        <br/>Выберите те, что нужно перенести в категорию
        <a target="_blank" href="/admin/category/category?categoryId=<?= $product->category_id ?>"><?= $product->categories->name ?></a> (невыбранные данные удалятся из продукта)
        <br/>Или просто верните продукт в категорию <a target="_blank" href="/admin/category/category?categoryId=<?= $categories->category_id ?>"><?= $categories->name ?></a>
    </p>

    <form method="post" id="upload" class="submitWarn" action="/admin/dolls/move" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
        <input type="hidden" name="category_id" value="<?= $product->category_id ?>"/>
        <input type="hidden" name="product_id" value="<?= $product->product_id ?>"/>

        <div class="row padding-bottom">
            <button type="submit" data-action="/admin/dolls/move" id="moveTo" name="save" value="1"
                    class="right btn btn-primary">Перенести в <?= $product->categories->name ?>
            </button>
            <button type="submit" data-action="/admin/dolls/move" id="returnTo" name="newCategoryId" value="<?= $categories->category_id ?>"
                    class="right btn btn-primary">Вернуть продукт в <?= $categories->name ?>
            </button>
        </div>


        <div class="form-group">
            <h3>
                <a name="Specs" target="_blank" href="/admin/specs?categoryId=<?= $product->category_id ?>">Характеристики</a>
            </h3>
            <input data-target="specs" type="checkbox" id="specs" class="batch">
            <label for="specs">выбрать все</label>
        </div>


        <div>
            <?php

            $i = 0;
            for ($extraSpecs->first(); $extraSpecs->current(); $extraSpecs->next(), $i++) {
                ?>
                <div class="form-group">
                    <input type="checkbox" id="specs<?= $extraSpecs->spec_id ?>" class="specs" name="specs[]" value="<?= $extraSpecs->spec_id ?>">
                    <span><?= $extraSpecs->specs->name ?>:</span>
                    <span><?= $extraSpecs->value ?>:</span>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="form-group">
            <h3>
                <a name="Options"  target="_blank" href="/admin/options?categoryId=<?= $product->category_id ?>">Опции</a>
            </h3>
            <input data-target="options" type="checkbox" id="options" class="batch">
            <label for="options">выбрать все</label>
        </div>

        <div>
            <?php
            $i = 0;
            for ($extraOptions->first(); $extraOptions->current(); $extraOptions->next()) {
                ?>
                <div class="form-group">
                    <input type="checkbox" id="options<?= $extraOptions->option_id ?>" class="options" name="options[]" value="<?= $extraOptions->option_id ?>">

                    <span><?= $extraOptions->name ?>:</span>
                </div>
                <?php
            }
            ?>
        </div>
    </form>


</div>