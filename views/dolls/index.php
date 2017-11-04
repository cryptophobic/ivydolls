<?php

use app\models\Category\CategoryPack;
use app\models\Products\ProductPresentation;
use app\widgets;

/**
 * @var ProductPresentation $productPresentation
 */

$product = $productPresentation->getProduct();
$specs = $productPresentation->getSpecs();
$options = $productPresentation->getOptions();
$relatedCategories = $productPresentation->getRelatedCategories();
$brands = $productPresentation->getBrands();

$this->title = yii::$app->params['company'] . ' ' . $product->brands->name . ' ' . $product->name;

$this->registerCssFile('/css/site.css');
$this->registerCssFile('/css/dolls.css');
$this->registerCssFile("/css/OwlCarousel/owl.carousel.min.css");
$this->registerCssFile("/css/OwlCarousel/owl.theme.default.min.css");
$this->registerJsFile('/js/OwlCarousel/owl.carousel.min.js');
$this->registerJsFile('/js/dolls.js');
$this->registerJsFile('/js/site.js');
$this->registerJsFile('https://www.google.com/recaptcha/api.js');

$this->registerCssFile('/lightbox2/dist/css/lightbox.min.css');
$this->registerJsFile('/lightbox2/dist/js/lightbox.min.js');




$active = 'active';


?>

<div class="container" style="margin-top:55px">
    <div class="row">
        <div class="col-sm-4 panel panel-default">
            <div id="carousel" class="carousel" data-interval="false">
                <div class="carousel-inner">
                    <?php
                    $productImages = $product->products_images;
                    if ($productImages->first()) {

                        for ($productImages->first(); $productImages->current(); $productImages->next()) {
                            ?>
                            <div class="item <?= $active ?>">
                                <a href="<?= $productImages->image_high ?>" data-lightbox="image-<?= $product->product_id ?>" data-title="<?= $product->name ?>">
                                <img id="<?= $productImages->products_image_id ?>image"
                                     data-id="<?= $productImages->products_image_id ?>"
                                     src="<?= $productImages->image_low ?>" alt="<?= $product->name ?>"/>
                                </a>
                            </div>
                            <?php
                            $active = '';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="row owl-carousel-container">
                <a href="#" class="prev fa fa-chevron-left" aria-hidden="true"></a>
                <a href="#" class="next fa fa-chevron-right" aria-hidden="true"></a>

                <div class="owl-carousel col-sm-12" data-items="5">
                    <?php
                    if ($productImages->first()) {
                        $index = 0;
                        for ($productImages->first(); $productImages->current(); $productImages->next()) {
                            ?>
                            <div style="padding: 10px;" data-target="#carousel" data-slide-to="<?= $index++ ?>">
                                <img data-id="<?= $productImages->products_image_id ?>"
                                     src="<?= $productImages->image_thumb ?>"
                                     alt="<?= $product->name ?>"/>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div> <!-- /col-sm-6 -->
        <div class="col-sm-8">
            <input type="hidden" name="initialPrice" value="<?= $product->price ?>">
            <input type="hidden" name="calculatedPrice" value="<?= $product->price ?>">
            <h1 id="60name" class="group inner list-group-item-heading">
                <?= $product->categories->name.": " ?> <?= $product->brands->name ?> <?= $product->name ?> <!--(<?= $product->part_number ?>) -->
            </h1>
            <table class="table productSummary">
                <thead>
                <tr>
                    <th>Цена</th>
                    <th class="text-right" id="priceContainer"><?= $product->price ?></th>
                </tr>
                </thead>
                <tbody>

                <?php
                if ($product->products_specs->first()) {
                    $productSpecs = $product->products_specs;
                    for ($productSpecs->first(); $productSpecs->current(); $productSpecs->next()) {
                        $specInfo = $productSpecs->specs;
                        $specInfo->first();

                        ?>
                        <tr>
                            <td><?= $specInfo->name ?></td>
                            <td class="text-right"><?= $productSpecs->value ?></td>
                        </tr>
                        <?php
                    }
                }

                $productsOptions = $product->products_options;
                $i = 0;
                for ($options->first(); $options->current(); $options->next()) {
                    if(!$productsOptions->filter("option_id", $options->option_id))
                    {
                        continue;
                    }
                    $restrictedValues = $options->options_restricted_values;
                    ?>
                    <tr class="form-group">
                        <td><label for="option<?= $options->option_id; ?>"><?= $options->name ?></label></td>
                        <td class="text-right">
                            <select class="priceCollector" name="option<?= $options->option_id; ?>" id="option<?= $options->option_id; ?>">
                                <?php
                                if ($restrictedValues->current()) {
                                    for ($restrictedValues->first(); $restrictedValues->current(); $restrictedValues->next(), $i++) {

                                        $moved = $productsOptions->moveToItem([
                                            'product_id' => $product->product_id,
                                            'option_id' => $options->option_id,
                                            'options_restricted_values_id' => $restrictedValues->options_restricted_values_id
                                        ]);
                                        if ($moved) {
                                            var_dump($productsOptions->toArray(), $restrictedValues->options_restricted_values_id);
                                            ?>
                                            <option data-price="<?= $productsOptions->price ?>" value="<?= $restrictedValues->options_restricted_values_id ?>">
                                                <?= $restrictedValues->value ?> (<?= $productsOptions->price ?> )
                                            </option>

                                            <?php
                                        }
                                    }
                                } else {
                                    ?>
                                <option value="N">Нет (0)</option>
                                <option data-price="<?= $productsOptions->price ?>" value="Y">Да (<?= $productsOptions->price ?> )</option>
                                <?php

                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php
                }
                ?>


                <!--<tr>
                    <td>Фуллсет</td>
                    <td align="right">
                        <input type="checkbox" name="fullset"/>
                    </td>
                </tr>-->
                </tbody>
            </table>
            <?php
            $related = $product->products_related;
            $i = 0;
            foreach ($relatedCategories as $categoryId => $relatedCategory) {
                /** @var CategoryPack $category */
                $category = $relatedCategory['category'];
                /** @var array $relatedKeys */
                $relatedKeys = $relatedCategory['relatedKeys'];
                ?>
                <div id="menu<?= $category->category_id ?>" >
                    <label for="inputRelatedCat<?= $category->category_id ?>"><?= $category->name ?></label>
                    <table id="inputRelatedCat<?= $category->category_id ?>" class="table" >
                        <tbody>
                        <?php
                        foreach ($relatedKeys as $keys) {
                            $i++;
                            $related->moveToItem($keys);
                            ?>
                            <tr>
                                <td class="col-sm-2">
                                    <input data-price="<?= $related->price ?>" class="priceCollector checkRelated<?= $category->category_id ?>" type="checkbox"
                                           name="relatedDelete[]"
                                           value="<?= $related->product_related_id ?>">
                                </td>
                                <td class="col-sm-4">
                                    <a href="/dolls/?productId=<?= urlencode($related->products->product_id) ?>">
                                        <img  src="<?= $related->products->products_images->image_thumb ?>">
                                    </a>
                                </td>

                                <td class="col-sm-3">
                                    <span><a href="/dolls/?productId=<?= urlencode($related->products->product_id) ?>"><?= $related->products->name ?></a></span>
                                </td>
                                <td class="text-right col-sm-3">
                                    <span><?= $related->price ?></span>
                                </td>
                            </tr>

                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Описание</a></li>
            <li><a data-toggle="tab" id="feedbackAnchor" name="feedbackAnchor" href="#menuFeedback">Задать вопрос</a></li>
        </ul>

        <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <h3>Описание</h3>
                <?= $product->description ?>
            </div>
            <div id="menuFeedback" class="tab-pane fade">
                <h3>Задать вопрос</h3>
                <form class="ajaxForm" action="/feedback" method="POST">
                    <h4 class="caption text-danger warnMessage"></h4>
                    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                    <div class="form-group">
                        <label for="inputName">Имя:</label>
                        <input type="text" required="true" class="form-control" name="name" id="inputName" value="">
                    </div>
                    <div class="form-group">
                        <label for="inputPhone">Телефон:</label>
                        <input type="text" class="form-control" name="phone" id="inputPhone" value="">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email:</label>
                        <input type="email" required="true" class="form-control" name="email" id="inputEmail" value="">
                    </div>
                    <div class="form-group">
                        <label for="inputMessage">Комментарий (вопрос):</label>
                        <textarea required="true" class="form-control" name="message" id="inputMessage"></textarea>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6LfZ8DQUAAAAAAPjclnl1drS7ihv5zzl_lS__qbe"></div>
                    <button type="submit" id="feedback" name="feedback" value="1"
                            class="btn btn-primary">Отправить сообщение
                    </button>

                </form>

            </div>
        </div>

    </div>
</div>

<?= widgets\CartModalWidget::widget() ?>
