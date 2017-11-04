<?php
use app\models\Brands\BrandsPack;

$this->registerCssFile('/kartik-v/bootstrap-fileinput/css/fileinput.min.css',['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/piexif.min.js',['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/sortable.min.js',['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/plugins/purify.min.js',['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/fileinput.min.js',['position' => static::POS_END]);
$this->registerJsFile('/kartik-v/bootstrap-fileinput/js/locales/ru.js',['position' => static::POS_END]);
$this->registerJsFile('/js/admin/uploader.js',['position' => static::POS_END]);

/**
 * @type BrandsPack $brand
 */

?>

<div class="container-fluid">
    <form method="POST" id="upload" action="/admin/brand/upload" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <input type="hidden" name="brand_id" id="brandId" value="<?= $brand->brand_id ?>">
        <input type="hidden" name="logo_thumb" id="logo_thumb" value="<?= $brand->logo_thumb ?>">
        <input type="hidden" name="logo" id="image" value="<?= $brand->logo ?>">
        <div class="row" id="errorContainer"></div>
        <div class="row">
            <div class="col-sm-12"><h2><?= $brand->name ?></h2></div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <img class="img-responsive"
                     data-id="<?= $brand->brand_id ?>"
                     src="<?= $brand->logo.'?'.rand(); ?>"
                     alt="<?= $brand->name ?>"/>
            </div>
            <div class="col-sm-9">
                <div class="form-group">
                    <label for="inputName">Бренд:</label>
                    <input type="text" class="form-control" name="name" id="inputName" value="<?= $brand->name ?>">
                </div>
                <div class="form-group">
                    <label for="inputUrl">Website:</label>
                    <input type="text" class="form-control" name="url" id="inputName" value="<?= $brand->url ?>">
                </div>
            </div>
        </div>
        <div class="row">
            <?php
                if ($brand->brand_id)
                {
            ?>
            <div class="col-sm-11">
                <input id="input-id" name="brandImage" type="file">
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