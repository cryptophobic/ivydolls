<?php

use app\models\Category\CategoryPack;
use app\models\Products\ProductPresentation;

$this->registerCssFile('/css/site.css');
$this->registerCssFile('/css/admin/site.css');

$this->registerJsFile("/ckeditor/ckeditor.js", ['position' => static::POS_END]);

$this->registerCssFile('/bootstrap-select/css/bootstrap-select.min.css', ['position' => static::POS_END]);
$this->registerJsFile('/bootstrap-select/js/bootstrap-select.min.js', ['position' => static::POS_END]);
$this->registerCssFile('/kartik-v/bootstrap-fileinput/css/fileinput.min.css', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/piexif.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/sortable.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/purify.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/fileinput.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/locales/ru.js', ['position' => static::POS_END]);
$this->registerJsFile('/js/admin/galleryUploader.js', ['position' => static::POS_END]);
$this->registerJsFile('/js/admin/product.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/themes/fa/theme.js', ['position' => static::POS_END]);


/**
 * @var ProductPresentation $productPresentation
 * @var CategoryPack $categories
 */

$product = $productPresentation->getProduct();
$specs = $productPresentation->getSpecs();
$options = $productPresentation->getOptions();
$relatedCategories = $productPresentation->getRelatedCategories();
$brands = $productPresentation->getBrands();

?>

<div class="container-fluid">
    <form method="post" id="upload" class="submitWarn" action="/admin/dolls/save" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
        <input type="hidden" name="product_id" value="<?= $product->product_id ?>"/>
        <input type="hidden" name="original_url" value="<?= $product->original_url ?>"/>

        <div class="row padding-bottom">
            <button type="submit" data-action="/admin/dolls/save" id="saveProduct" name="save" value="1"
                    class="right btn btn-primary">Применить изменения
            </button>
        </div>

        <div class={row}>
        </div>

        <?php if ($product->product_id !== null) {
            ?>
            <div class="form-group">
                <h3>
                    <a name="Images" href="#Images" data-toggle="collapse" data-target="#images">
                        Изображения
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </a>
                </h3>
            </div>

            <div id="images" class="collapse">
                <input class="batch" data-target="images" id="imagesCheck" type="checkbox">&nbsp;<label for="imagesCheck">Выбрать
                    все изображения</label>


                <div class="row">
                    <?php
                    $images = $product->products_images;
                    for ($images->first(); $images->current(); $images->next()) {
                        ?>
                        <div class="col-sm-1 padding-bottom">
                            <input class="images" id="img<?= $images->products_image_id ?>" type="checkbox"
                                   name="imagesDelete[products_image_id][]"
                                   value="<?= $images->products_image_id ?>">
                            <label for="img<?= $images->products_image_id ?>">
                                <div class="galleryAdmin <?php if ($images->main == 1) { ?>framed<?php } ?>"
                                     id="gallery<?= $images->products_image_id ?>">
                                    <img class="dropbtn" data-id="<?= $images->products_image_id ?>"
                                         src="<?= $images->image_thumb ?>"
                                         alt="<?= $product->name ?>"/>
                                    <div class="galleryAdmin-content">
                                        <a class="activate"
                                           data-product_id="<?= $product->product_id ?>"
                                           data-products_image_id="<?= $images->products_image_id ?>"
                                           data-action="/admin/dolls/activate"
                                           href="#Images">Active</a>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="row">
                    <input id="input-id" name="newImages[]" type="file" multiple>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="form-group">
            <h3>
                <a name="Related" href="#Related" data-toggle="collapse" data-target="#general">
                    Общая информация
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </a>
            </h3>
        </div>

        <div id="general" class="collapse in">

            <div class="form_group">
                <label for="category_id">Категория:</label>
                <select id="category_id" name="category_id" class="required selectpicker">
                    <?php
                    for ($categories->first(); $categories->current(); $categories->next()) {
                        ?>
                        <option value="<?= $categories->category_id ?>"
                                <?php if ($product->category_id == $categories->category_id){ ?>selected<?php } ?>>
                            <?= $categories->name ?></option>
                        <?php
                    }
                    ?>
                </select>
                <label for="brand_id">Бренд:</label>
                <select id="brand_id" name="brand_id" class="required selectpicker">
                    <option value=""></option>
                    <?php
                    for ($brands->first(); $brands->current(); $brands->next()) {
                        ?>
                        <option value="<?= $brands->brand_id ?>"
                                <?php if ($product->brand_id == $brands->brand_id){ ?>selected<?php } ?>>
                            <?= $brands->name ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="inputName">Имя:</label>
                <input type="text" class="required form-control" id="inputName" name="name"
                       value="<?= $product->name ?>">
            </div>

            <div class="form-group">
                <label for="inputName">Артикульный номер:</label>
                <input type="text" class="form-control" id="inputName" name="part_number"
                       value="<?= $product->part_number ?>">
            </div>

            <div class="form-group">
                <label for="inputPrice">Цена:</label>
                <input type="text" class="required form-control" id="inputPrice" name="price"
                       value="<?= $product->price ?>">
            </div>

            <td>
                <label for="inputDescription">Описание:</label>
                <textarea id="inputDescription"
                          name="description"><?= $product->description ?></textarea>
            </td>
        </div>


        <div class="form-group">
            <h3>
                <a name="Specs" href="#Specs" data-toggle="collapse" data-target="#specs">
                    Характеристики
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </a>
            </h3>
        </div>


        <div id="specs" class="collapse">
            <?php

            $productsSpecs = $product->products_specs;
            $i = 0;
            for ($specs->first(); $specs->current(); $specs->next(), $i++) {
                $productsSpecs->moveToItem([
                    'product_id' => $product->product_id,
                    'spec_id' => $specs->spec_id
                ]);
                ?>
                <div class="form-group">
                    <label for="inputSpec<?= $specs->spec_id ?>"><?= $specs->name ?>:</label>
                    <input type="hidden" name="products_specs[<?= $i ?>][spec_id]" value="<?= $specs->spec_id ?>">
                    <input type="text" class="form-control" name="products_specs[<?= $i ?>][value]"
                           id="inputSpec<?= $specs->spec_id ?>"
                           value="<?= $productsSpecs->value ?>">
                </div>
                <?php
            }
            ?>
        </div>

        <div class="form-group">
            <h3>
                <a name="Options" href="#Options" data-toggle="collapse" data-target="#options">
                    Опции
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </a>
            </h3>
        </div>

        <div id="options" class="collapse">

            <?php

            $productsOptions = $product->products_options;
            $i = 0;
            for ($options->first(); $options->current(); $options->next()) {
                $restrictedValues = $options->options_restricted_values;
                ?>
                <div class="form-group">

                    <label for="inputOption<?= $options->option_id ?>"><?= $options->name ?>:</label>
                    <a name="Option<?= $options->option_id ?>" href="#Option<?= $options->option_id ?>"
                       data-toggle="collapse" data-target="#inputOption<?= $options->option_id ?>">Значения</a>

                    <table id="inputOption<?= $options->option_id ?>" class="collapse in table">
                        <thead>
                        <tr>
                            <th>
                                <input data-target="options<?= $options->option_id ?>" type="checkbox"
                                       id="options<?= $options->option_id ?>" class="batch">
                                <label for="options<?= $options->option_id ?>">Вкл</label>
                            </th>
                            <th>Название</th>
                            <th class="text-right">Цена</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($restrictedValues->current()) {
                            for ($restrictedValues->first(); $restrictedValues->current(); $restrictedValues->next(), $i++) {

                                $moved = $productsOptions->moveToItem([
                                    'product_id' => $product->product_id,
                                    'option_id' => $options->option_id,
                                    'options_restricted_values_id' => $restrictedValues->options_restricted_values_id
                                ]);
                                ?>
                                <tr>
                                    <td>
                                        <input
                                                class="checkOption options<?= $options->option_id ?>"
                                                data-id="price<?= $options->option_id . $restrictedValues->options_restricted_values_id ?>"
                                                type="checkbox" <?= $moved === true ? 'checked=“checked”' : ''; ?>
                                                name="products_options[<?= $i ?>][option_id]"
                                                value="<?= $options->option_id ?>">
                                    </td>
                                    <td>
                                        <span><?= $restrictedValues->value ?></span>
                                    </td>
                                    <td class="text-right">
                                        <input type="hidden"
                                               name="products_options[<?= $i ?>][options_restricted_values_id]"
                                               value="<?= $restrictedValues->options_restricted_values_id ?>">
                                        <input
                                            <?php if (!$moved) { ?>disabled<? } ?>
                                            id="price<?= $options->option_id . $restrictedValues->options_restricted_values_id ?>"
                                            type="number"
                                            step="any"
                                            name="products_options[<?= $i ?>][price]"
                                            value="<?= $productsOptions->price === null ? $restrictedValues->price : $productsOptions->price; ?>">
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            $moved = $productsOptions->moveToItem([
                                'product_id' => $product->product_id,
                                'option_id' => $options->option_id,
                                'options_restricted_values_id' => 0
                            ]);
                            ?>
                            <tr>

                                <td>
                                    <input
                                            class="checkOption options<?= $options->option_id ?>"
                                            data-id="price<?= $options->option_id ?>0"
                                            type="checkbox" <?= $moved === true ? 'checked=“checked”' : ''; ?>
                                            name="products_options[<?= $i ?>][option_id]"
                                            value="<?= $options->option_id ?>">
                                </td>
                                <td>
                                    <span>Стоимость за опцию</span>
                                </td>
                                <td class="text-right">
                                    <input
                                        <?php if (!$moved) { ?>disabled<? } ?>
                                        id="price<?= $options->option_id ?>0"
                                        type="number"
                                        step="any"
                                        name="products_options[<?= $i ?>][price]"
                                        value="<?= $productsOptions->price === null ? $options->price : $productsOptions->price; ?>">
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="form-group">
            <h3>
                <a name="related" href="#related" data-toggle="collapse" data-target="#related">
                    Связанные товары
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </a>
            </h3>
        </div>

        <div id="related" class="collapse">
            <div class="form-group">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Добавить
                    товар
                </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header>
                                    <button type=" button
                        " class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Связанные продукты</h4>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="input-group col-sm-12">
                                <input type="text" class="form-control suggestions" placeholder="Название или артикул"
                                       value="">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" data-action="/admin/dolls/index" type="submit"><i
                                                class="glyphicon glyphicon-search"></i></button>
                                    <button type="submit" class="btn btn-default">Добавить товары</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="suggest-container" class="col-sm-12">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>


        <?php
        $related = $product->products_related;
        $i = 0;
        foreach ($relatedCategories as $categoryId => $relatedCategory) {
            /** @var CategoryPack $category */
            $category = $relatedCategory['category'];
            /** @var array $relatedKeys */
            $relatedKeys = $relatedCategory['relatedKeys'];
            ?>

            <a name="Related<?= $category->category_id ?>" href="#Related<?= $category->category_id ?>"
               data-toggle="collapse"
               data-target="#inputRelatedCat<?= $category->category_id ?>"><?= $category->name ?>:</a>

            <table id="inputRelatedCat<?= $category->category_id ?>" class="collapse in table">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="batch" id="checkRelated<?= $category->category_id ?>"
                               data-target="checkRelated<?= $category->category_id ?>">
                        <label for="checkRelated<?= $category->category_id ?>"><i class="fa fa-trash-o"
                                                                                  aria-hidden="true"></i>
                        </label>
                    </th>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th class="text-right">Цена</th>
                </tr>
                </thead>
                <tbody>
                <?php

                foreach ($relatedKeys as $keys) {
                    $i++;
                    $related->moveToItem($keys);
                    ?>
                    <tr>
                        <td class="col-sm-2">
                            <input class="checkRelated<?= $category->category_id ?>" type="checkbox"
                                   name="relatedDelete[]"
                                   value="<?= $related->product_related_id ?>">
                        </td>
                        <td class="col-sm-4">
                            <img style="height: 50px" src="<?= $related->products->products_images->image_thumb ?>">
                        </td>

                        <td class="col-sm-3">
                            <span><a href="/admin/dolls/doll?id=<?= $related->product_related_id ?>"><?= $related->products->name ?></a></span>
                        </td>
                        <td class="text-right col-sm-3">
                            <span><?= $related->products->price ?></span>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <?php
        }
        ?>
    </form>
</div>