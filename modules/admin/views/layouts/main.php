<?php use yii\helpers\Html; ?>

<?php $this->beginPage() ?>

<?php
$this->registerJsFile('/js/jquery-3.2.0.min.js',['position' => static::POS_HEAD]);
$this->registerJsFile('/js/admin/common.js',['position' => static::POS_HEAD]);
$this->registerCssFile('/css/admin/site.css',['position' => static::POS_HEAD]);
$this->registerCssFile('/bootstrap/css/bootstrap.min.css',['position' => static::POS_HEAD]);
$this->registerCssFile('/bootstrap/css/bootstrap-theme.min.css',['position' => static::POS_HEAD]);
$this->registerCssFile('/css/font-awesome-4.7.0/css/font-awesome.min.css',['position' => static::POS_HEAD]);
$this->registerJsFile('/bootstrap/js/bootstrap.min.js',['position' => static::POS_HEAD]);
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
<?php $this->beginBody() ?>
<div id="overlay"></div>
<div class="container-fluid">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/"><?= yii::$app->params['company'] ?></a>
            </div>
            <ul class="nav navbar-nav">
                <li class="menu-item <?php if($this->context->id == 'dolls') echo 'active'?>"><a href="/admin/dolls/index">Продукты</a></li>
                <li class="menu-item <?php if($this->context->id == 'category') echo 'active'?>"><a href="/admin/category/index">Категории</a></li>
                <li class="menu-item <?php if($this->context->id == 'brand') echo 'active'?>"><a href="/admin/brand/index">Бренды</a></li>
                <li class="menu-item <?php if($this->context->id == 'feedback') echo 'active'?>"><a href="/admin/feedback/index">Сообщения</a></li>
            </ul>
        </div>
    </nav>
    <div id="main" class="panel panel-default">
        <div class="panel-body">
            <input type="hidden" name="url" value="<?= $this->context->contentUrl ?>">
            <div class="col-sm-10" id="mainCol">
                <div class="row" id="content-container">
                    <?= $content; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>

<?php $this->endPage() ?>
