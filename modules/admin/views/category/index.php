<?php
use app\models\Category\CategoryPack;
use yii\web\View;

/**
 * @type CategoryPack $categories
 * @var View $this
 * @var int $parentCategoryId
 */

$this->registerJsFile('/js/admin/common.js',['position' => static::POS_END]);
$this->registerJsFile('/js/admin/dragndrop.js',['position' => static::POS_END]);
$this->registerCssFile('/bootstrap-select/css/bootstrap-select.min.css', ['position' => static::POS_END]);
$this->registerJsFile('/bootstrap-select/js/bootstrap-select.min.js', ['position' => static::POS_END]);


?>
<form class="submitWarn" action="/admin/category/delete" method="post">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="container-fluid">
    <div class="row">
        <a href="/admin/category/category?parentCategoryId=<?= $parentCategoryId ?>" type="button" class="btn btn-primary">Новая категория</a>
        <button type="submit" id="deleteCategory" class="btn btn-primary">Удалить</button>
        <select id="filter2" name="parentCategoryId" data-method="GET" data-action="/admin/category/index"  class="submitiative selectpicker">
            <option value="0">Родительская категория</option>
            <?php
            for ($categories->first(); $categories->current(); $categories->next()) {
                ?>
                <option value="<?= $categories->category_id ?>"
                        <?php if ($categories->category_id == $parentCategoryId){ ?>selected<?php } ?>>
                    <?= $categories->name ?></option>
                <?php
            }
            ?>
        </select>

    </div>
    <table class="table">
        <thead>
        <tr>
            <th><nobr><input type="checkbox" id="selectAll" value=""></nobr></th>
            <th>Изображение</th>
            <th>Имя, описание&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></th>
            <th>Опции</th>
            <th>Спецификации</th>
        </tr>
        </thead>
        <tbody>

        <?php


        for ($categories->first();$categories->current();$categories->next()) {
            if ($categories->parent_category_id != $parentCategoryId)
            {
                continue;
            }
        ?>

            <tr draggable="true" data-id="<?= $categories->category_id ?>" data-no="<?= $categories->no ?>"
                data-action="/admin/category/move?categoryId=<?= $categories->category_id ?>">
                <td class="col-sm-2">
                    <input type="checkbox" class="categoryCheck" name="categoryIds[]" value="<?= $categories->category_id ?>" />
                </td>
                <td class="col-sm-2">
                    <a href="/admin/category/category?categoryId=<?= $categories->category_id ?>"><img
                        id="<?= $categories->category_id ?>image"
                        src="<?= $categories->image_thumb.'?rand='.rand(0,100); ?>"
                        alt="<?= $categories->name ?>"/></a>
                </td>
                <td class="col-sm-2">
                    <a href="/admin/category/category?categoryId=<?= $categories->category_id ?>"><?= $categories->name ?><br></a>
                    <?= $categories->description ?>

                </td>
                <td class="col-sm-2">
                    <a href="/admin/options?categoryId=<?= $categories->category_id ?>">Опции</a>
                </td>
                <td class="col-sm-2">
                    <a href="/admin/specs?categoryId=<?= $categories->category_id ?>">Спеки</a>
                </td>
                <td class="col-sm-2">
                    <a href="/admin/category/index?parentCategoryId=<?= $categories->category_id ?>">
                        Подкатегории
                    </a>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
</form>