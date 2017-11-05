<?php

use app\models\Products\ProductsCollection;
use app\widgets;

/* @var $this yii\web\View */

$this->registerCssFile('/css/site.css');
$this->registerCssFile('/css/start.css');
$this->registerCssFile('/css/sideMenu.css');
$this->registerJsFile('/js/site.js');
$this->registerJsFile('/js/sideMenu.js');
$this->registerJsFile('/js/cartItem.js');
$this->registerJsFile('/js/items.js');
$this->registerJsFile('/js/start.js');

/** @var \app\models\Category\CategoryPack $categories */

?>

<div id="myCarousel" class="row carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
        <li data-target="#myCarousel" data-slide-to="3"></li>
        <li data-target="#myCarousel" data-slide-to="4"></li>
        <li data-target="#myCarousel" data-slide-to="5"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">

        <div class="item active">
            <img data-href="/dolls?productId=118" src="/images/carousel/FuSangAncient.jpg" alt="Flower" width="460" height="345">
            <div class="carousel-caption">
                <h3>Fu Sang (Ancient)</h3>
                <p></p>
            </div>
        </div>

        <div class="item">
            <img data-href="/dolls?productId=119" src="/images/carousel/FuSangModern.jpg" alt="Flower" width="460" height="345">
            <div class="carousel-caption">
                <h3>Fu Sang (Modern)</h3>
                <p></p>
            </div>
        </div>

        <div class="item">
            <img data-load="/start/load-products?productIds=121,120" src="/images/carousel/Mirror.jpg" alt="Flower" width="460" height="345">
            <div class="carousel-caption">
                <h3>Live in Mirror</h3>
                <p></p>
            </div>
        </div>

        <div class="item">
            <img data-href="/dolls?productId=186" src="/images/carousel/Chaos.jpg" alt="Chania" width="460" height="345">
            <div class="carousel-caption">
                <h3>Chaos</h3>
                <p></p>
            </div>
        </div>

        <div class="item">
            <img data-href="/dolls?productId=191" src="/images/carousel/ChaosBeast.jpg" alt="Chania" width="460" height="345">
            <div class="carousel-caption">
                <h3>Chaos (Beast version)</h3>
                <p></p>
            </div>
        </div>

        <div class="item">
            <img data-load="/start/load-products?productIds=128,199" src="/images/carousel/MarkusTaowu.jpg" alt="Flower" width="460" height="345">
            <div class="carousel-caption">
                <h3>Marcus & Taowu</h3>
                <p></p>
            </div>
        </div>

    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div class="container-fluid">

    <div class="row" id="assortment">
        <hr>
        <!--<div class="col-sm-3">
        </div>
        <div class="col-sm-9 form-inline">
            <a href="#" class="iconMedium rightSpace"><i class="fa fa-mars" aria-hidden="true"></i></a>
            <a href="#" class="iconMedium"><i class="fa fa-venus" aria-hidden="true"></i></a>
        </div>
        <hr>-->
    </div>

    <div class="row">
        <div class="col-sm-2" id="leftCol">

            <ul class="nav nav-stacked" id="sidebar">
                <?php
                for ($categories->first(); $categories->current(); $categories->next()) {
                    ?>
                    <li class="panel" id="heightPanel">
                        <?php
                        if($categories->category_id == 112) {
                            ?>
                            <a href="/#sidebar-dolls" data-toggle="collapse"
                               data-parent="#sidebar"><?= $categories->name ?></a>
                            <ul class="nav collapse" id="sidebar-dolls">
                                <?
                                foreach (ProductsCollection::heights() as $height => $description) {
                                    ?>
                                    <li><a class="sidebar-items" href="/start/load-products?height=<?= $height ?>&categoryId=<?= $categories->category_id ?>"><?= $description ?></a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                        elseif ($categories->categories->first())
                        {
                            ?>
                            <a href="/#sidebar-<?= $categories->category_id ?>" data-toggle="collapse" data-parent="#sidebar"><?= $categories->name ?></a>
                            <ul class="nav collapse" id="sidebar-<?= $categories->category_id ?>">
                                <?
                                $children = $categories->categories;
                                for ($children->first();$children->current();$children->next()) {
                                    ?>
                                    <li><a class="sidebar-items" href="/start/load-products?categoryId=<?= $children->category_id ?>"><?= $children->name ?></a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>

                            <?php
                        } else
                        {
                            ?>
                            <a
                                    class="sidebar-items"
                                    href="/start/load-products?categoryId=<?= $categories->category_id ?>"
                                    name="cat<?= $categories->category_id ?>" href="#cat<?= $categories->category_id ?>" data-parent="#sidebar">
                                <?= $categories->name ?>
                            </a>
                        <?php

                        }
                        ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>

        <input type="hidden" name="scrollUrl" value="<?= $scrollUrl ?>">
        <div class="col-sm-10" id="mainCol">
            <div class="row" id="content-container">
            </div>
        </div>
    </div>
</div>

<?= widgets\CartModalWidget::widget() ?>
