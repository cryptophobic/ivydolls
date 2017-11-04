<?php
use app\models\Brands\BrandsPack;
use yii\web\View;

/**
 * @type BrandsPack $brands
 * @var View $this
 */

$this->registerJsFile('/js/admin/common.js',['position' => static::POS_END]);

?>
<form class="submitWarn" action="/admin/brand/delete" method="post">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
<div class="container-fluid">
    <table class="table">
        <thead>
        <tr>
            <th><nobr><input type="checkbox" id="selectAll" value=""></nobr></th>
            <th>Изображение</th>
            <th>Имя&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></th>
            <th>Website</th>
            <th><a href="/admin/brand/brand" type="button" class="btn btn-primary">Новый бренд</a></th>
            <th><button type="submit" id="deleteBrand" class="btn btn-primary">Удалить</button></th>
        </tr>
        </thead>
        <tbody>

        <?php


        for ($brands->first();$brands->current();$brands->next()) {
        ?>

            <tr data-url="/admin/brand/brand?brandId=<?= $brands->brand_id ?>">
                <td class="col-sm-2">
                    <input type="checkbox" class="brandCheck" name="brandIds[]" value="<?= $brands->brand_id ?>" />
                </td>
                <td class="col-sm-2 loadable">
                    <img
                        id="<?= $brands->brand_id ?>image"
                        src="<?= $brands->logo_thumb.'?rand='.rand(0,100); ?>"
                        alt="<?= $brands->name ?>"/>
                </td>
                <td class="col-sm-2 loadable">
                    <?= $brands->name ?><br>
                </td>
                <td class="col-sm-4">
                    <a href="<?= $brands->url ?>"><?= $brands->url ?></a>
                </td>
                <td class="col-sm-2">
                    <a href="/admin/brand/brand?brandId=<?= $brands->brand_id ?>">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    </a>
                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
</form>