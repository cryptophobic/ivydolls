<?php

use app\models\Products\ProductsCollection;
use app\widgets;

/* @var $this yii\web\View */

$this->registerCssFile('/css/start.css');
$this->registerCssFile('/css/sideMenu.css');
$this->registerJsFile('/js/sideMenu.js');
//$this->registerJsFile('/js/cartItem.js');
$this->registerJsFile('/js/items.js');
$this->registerJsFile('/js/start.js');

/**
 * @var \app\models\Category\CategoryPack $categories
 * @var \app\models\Category\CategoryPack $category
 * @var \app\models\Products\ProductsSearch $productsSearch
 */

?>

<?= widgets\CarouselWidget::widget() ?>

<div class="container-fluid">
    <div class="row" id="assortment">
        <a href="#" id="sidebarToggler" data-target="#sidebar" data-toggle="collapse" class="text-muted">
            <i class="fa fa-navicon"></i>&nbsp;
            <?php
            foreach ($productsSearch->getFilters() as $filter => $value)
            {
                echo $filter .' "'.$value.'" ';
            }

            echo "результатов ".$productsSearch->getCount();

            ?>
        </a>

        <div class="col-sm-12">
            <h2></h2>
        </div
    </div>

    <div class="row d-flex d-md-block flex-nowrap wrapper">

        <?= widgets\SideMenuWidget::widget(['categories' => $categories]) ?>

        <input type="hidden" name="scrollUrl" value="<?= $productsSearch->getScrollUrl() ?>">
        <main id="mainContainer" class="col-md-10 float-left col px-5 pl-md-2 pt-2 main" style="width:100%">
            <div class="row" id="content-container">
                <?= $content ?>
            </div>
        </main>
    </div>
</div>

<?= ""/*widgets\CartModalWidget::widget()*/ ?>
