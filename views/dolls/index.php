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
$brands = $productPresentation->getBrands();

$this->title = yii::$app->params['company'] . ' ' . $product->brands->name . ' ' . $product->name;

$this->registerCssFile('/css/site.css');
$this->registerCssFile('/css/dolls.css');
$this->registerJsFile('/js/dolls.js');
$this->registerJsFile('/js/site.js');
$this->registerCssFile('/fotorama-4.6.4/fotorama.css');
$this->registerJsFile('/fotorama-4.6.4/fotorama.js');

$this->registerJsFile('https://www.google.com/recaptcha/api.js');

$active = 'active';

?>

<div class="container">
    <div class="row">
        <div class="col-4 panel panel-default">
            <div class="fotorama" data-allowfullscreen="true">
                <?php
                $productImages = $product->products_images;
                if ($productImages->first()) {
                    for ($productImages->first(); $productImages->current(); $productImages->next()) {
                        ?>
                <a href="<?= $productImages->image_high ?>" data-lightbox="image-<?= $product->product_id ?>" data-title="<?= $product->name ?>">
                    <img id="<?= $productImages->products_image_id ?>image"
                         data-id="<?= $productImages->products_image_id ?>"
                         src="<?= $productImages->image_low ?>" alt="<?= $product->name ?>"/>
                </a>
                        <?php
                    }
                }
                ?>
            </div>
        </div> <!-- /col-sm-6 -->
        <div class="col-8">
            <input type="hidden" name="initialPrice" value="<?= $product->price ?>">
            <input type="hidden" name="calculatedPrice" value="<?= $product->price ?>">
            <h1 id="60name" class="group inner list-group-item-heading">
                <?= $product->categories->name.": " ?> <?= $product->brands->name ?> <?= $product->name ?> <!--(<?= $product->part_number ?>) -->
            </h1>
            <table class="table productSummary table-sm">
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

        </div>
    </div>


    <div class="row">
        <ul class="nav nav-tabs container-fluid" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#relatedTab">Опции и аксессуары</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#descriptionTab">Описание</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" id="feedbackAnchor" name="feedbackAnchor" href="#feedBackTab">Задать вопрос</a></li>
        </ul>
    </div>

    <div class="row tab-content">
        <div id="relatedTab" class="tab-pane active  container-fluid">
            <?= widgets\ProductRelatedWidget::widget(['productPresentation' => $productPresentation]) ?>
        </div>


        <div id="descriptionTab" class="tab-pane container-fluid">
                <h3>Описание</h3>
                <?= $product->description ?>
        </div>
        <div id="feedBackTab" class="tab-pane container-fluid">
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

<?= "";/*widgets\CartModalWidget::widget()*/ ?>
