<?php

/**
 * @var \app\models\Category\CategoryPack $categories
 */

use app\models\Products\ProductsCollection;

?>

<div class="col-sm-2 float-left col-1 pl-0 pr-0 collapse width show" id="sidebar">
    <div class="list-group border-0 card text-center text-sm-left" id="sidebar-internal">
        <?php
        for ($categories->first(); $categories->current(); $categories->next()) {
            if ($categories->category_id == 112) {
                ?>
                <a href="#sidebar-dolls" class="list-group-item d-inline-block collapsed" data-toggle="collapse"
                   data-parent="#sidebar" aria-expanded="false">
                    <?= $categories->name ?>
                </a>
                <div class="collapse" id="sidebar-dolls">
                    <?
                    foreach (ProductsCollection::heights() as $height => $description) {
                        ?>

                        <a class="list-group-item" data-parent="#sidebar-dolls"
                           href="/start?height=<?= $height ?>&categoryId=<?= $categories->category_id ?>"><?= $description ?></a>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } elseif($categories->categories->first()) {
                ?>
                <a href="#sidebar-<?= $categories->category_id ?>" class="list-group-item d-inline-block collapsed" data-toggle="collapse"
                   data-parent="#sidebar" aria-expanded="false">
                    <?= $categories->name ?>
                </a>
                <div class="collapse" id="sidebar-<?= $categories->category_id ?>">
                    <?
                    $children = $categories->categories;
                    for ($children->first(); $children->current(); $children->next()) {
                        ?>

                        <a class="list-group-item" data-parent="#sidebar-dolls"
                           href="/start?categoryId=<?= $children->category_id ?>"><?= $children->name ?></a>
                        <?php
                    }
                    ?>
                </div>
                <?php

            } else {
                ?>
                <a href="/start?categoryId=<?= $categories->category_id ?>" class="list-group-item d-inline-block collapsed"
                   data-parent="#sidebar">
                    <?= $categories->name ?>
                </a>
                <?php
            }
        }
        ?>
    </div>
</div>
