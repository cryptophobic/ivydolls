<?php

use app\models\Brands\BrandsPack;
use app\models\Category\CategoryPack;
use app\models\Products\ProductsCollection;
use app\models\Products\ProductsPack;


$this->registerCssFile('/bootstrap-select/css/bootstrap-select.min.css',['position' => static::POS_END]);
$this->registerJsFile('/bootstrap-select/js/bootstrap-select.min.js',['position' => static::POS_END]);

/**
 * @var ProductsPack $items
 * @var CategoryPack $categories
 * @var BrandsPack $brands
 * @var int $selectedHeight
 */

$categoryId = $categories->selected()->category_id !== null ? $categories->selected()->category_id : 1;

?>

<form method="post" class="submitWarn" action="/admin/dolls/index">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>

    <div class="container-fluid">
        <div class="col-sm-3">
            <select id="filter1" data-action="/admin/dolls/index" name="brandId" class="submitiative selectpicker">
                <option value="0">бренд</option>
                <?php
                for ($brands->first(); $brands->current(); $brands->next())
                {
                    ?>
                    <option value="<?= $brands->brand_id ?>" <?php if ($brands->selected()->brand_id == $brands->brand_id){?>selected<?php }?>>
                        <?= $brands->name ?></option>
                    <?php
                }
                ?>
            </select>
        </div>

        <div class="col-sm-3">
            <select id="filter2" data-action="/admin/dolls/index" name="categoryId" class="submitiative selectpicker">
                <option value="0">категория</option>
                <?php
                for ($categories->first(); $categories->current(); $categories->next())
                {
                    ?>
                    <option value="<?= $categories->category_id ?>" <?php if ($categories->selected()->category_id == $categories->category_id){?>selected<?php }?>>
                        <?= $categories->name ?></option>
                    <?php
                }
                ?>
            </select>
        </div>


        <? if($categories->selected()->category_id == 1) {?>
        <div class="col-sm-3">
            <select id="filter3" data-action="/admin/dolls/index" name="height" class="submitiative selectpicker">
                <option value="0">рост</option>
                <?php
                foreach (ProductsCollection::heights() as $height => $description)
                {
                    ?>
                    <option value="<?= $height ?>" <?php if ($height == $selectedHeight){?>selected<?php }?>>
                        <?= $description ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <? } ?>
        <div class="col-sm-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Найти" name="keyword" value="<?= $keyword ?>">
                <div class="input-group-btn">
                    <button class="btn btn-default" data-action="/admin/dolls/index" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>

    </div>
    <div class="container-fluid">

        <table class="table">
            <thead>
            <tr>
                <th>
                    <nobr><input type="checkbox" id="selectAll" value=""></nobr>
                </th>
                <th>Изображение</th>
                <th>Имя, описание&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></th>
                <th>Спецификации</th>
                <th>Цена&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></th>
                <th>
                    <button type="submit" data-action="/admin/dolls/new?categoryId=<?= $categoryId ?>"
                            class="btn btn-primary">Новый
                    </button>
                </th>
                <th>
                    <button type="submit" data-action="/admin/dolls/delete" id="deleteProduct" class="btn btn-primary">
                        Удалить
                    </button>
                </th>

            </tr>
            </thead>
            <tbody>

            <?php
            for ($items->first(); $items->current(); $items->next()) {
                ?>

                <tr data-url="/admin/dolls/doll?id=<?= $items->product_id ?>">
                    <td>
                        <input type="checkbox" name="productIds[]" value="<?= $items->product_id ?>"/>
                    </td>
                    <td class="col-sm-3 loadable">
                        <?php
                        $items->products_images->first();
                        ?>
                        <img
                                id="<?= $items->product_id ?>image"
                                src="<?= $items->products_images->image_thumb ?>"
                                alt="<?= $items->name ?>"/>
                    </td>
                    <td class="col-sm-3"><a href="/admin/dolls/doll?id=<?= $items->product_id ?>"><?= $items->name ?></a></td>
                    <td class="col-sm-3"> <?php
                        if ($items->products_specs->first()) {
                            $productsSpecs = $items->products_specs;
                            for ($productsSpecs->first(); $productsSpecs->current(); $productsSpecs->next()) {
                                $productsSpecs->specs->first();
                                echo $productsSpecs->specs->name . "=" . $productsSpecs->value . " см";
                            }
                        }
                        ?>
                    </td>
                    <td class="col-sm-3"><?= $items->price ?></td>
                    <td>
                        <a href="/admin/dolls/doll?id=<?= $items->product_id ?>">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
            <tr>
                <td colspan="7">
                    <a href="<?= $firstUrl ?>">Начало</a>
                    <a href="<?= $prevUrl ?>">Назад</a>
                    <a href="<?= $nextUrl ?>">Вперёд</a>
                </td>
            </tr>
        </table>
    </div>

</form>