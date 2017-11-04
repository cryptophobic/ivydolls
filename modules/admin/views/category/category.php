<?php

use app\models\Category\CategoryPack;

$this->registerCssFile('/kartik-v/bootstrap-fileinput/css/fileinput.min.css', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/piexif.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/sortable.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/purify.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/fileinput.min.js', ['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/locales/ru.js', ['position' => static::POS_END]);
$this->registerJsFile('/js/admin/uploader.js', ['position' => static::POS_END]);
$this->registerCssFile('/bootstrap-select/css/bootstrap-select.min.css', ['position' => static::POS_END]);
$this->registerJsFile('/bootstrap-select/js/bootstrap-select.min.js', ['position' => static::POS_END]);


/**
 * @type CategoryPack $category
 * @type CategoryPack $categories
 */

?>

<div class="container-fluid">
    <form method="POST" id="upload" action="/admin/category/upload" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>
        <input type="hidden" name="category_id" id="categoryId" value="<?= $category->category_id ?>">
        <input type="hidden" name="image_thumb" id="image_thumb" value="<?= $category->image_thumb ?>">
        <input type="hidden" name="image" id="image" value="<?= $category->image ?>">
        <div class="row" id="errorContainer"></div>
        <div class="row">
            <div class="col-sm-12"><h2><?= $category->name ?></h2></div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <img class="img-responsive"
                     data-id="<?= $category->category_id ?>"
                     src="<?= $category->image . '?' . rand(); ?>"
                     alt="<?= $category->name ?>"/>
            </div>
            <div class="col-sm-9">
                <div class="form-group">
                    <label for="inputName">Родительская категория:</label>

                    <select id="filter2" name="parent_category_id" class="selectpicker">
                        <option value="0">Родительская категория</option>
                        <?php
                        for ($categories->first(); $categories->current(); $categories->next()) {
                            ?>
                            <option value="<?= $categories->category_id ?>"
                                    <?php if ($categories->category_id == $category->parent_category_id){ ?>selected<?php } ?>>
                                <?= $categories->name ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="inputName">Категория:</label>
                    <input type="text" class="form-control" name="name" id="inputName" value="<?= $category->name ?>">
                </div>
                <div class="form-group">
                    <label for="inputDesc">Описание:</label>
                    <textarea class="form-control" name="description"
                              id="inputDesc"><?= $category->description ?></textarea>
                </div>
                <?php
                if ($category->category_id != null) {
                    ?>
                    <div>
                        <a href="/admin/options?categoryId=<?= $category->category_id ?>">Опции</a>
                    </div>
                    <div>
                        <a href="/admin/specs?categoryId=<?= $category->category_id ?>">Спеки</a>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
        <div class="row">
            <?php
            if ($category->category_id) {
                ?>
                <div class="col-sm-11">
                    <input id="input-id" name="catImage" type="file">
                </div>
                <?php
            }
            ?>
            <div class="col-sm-1">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>