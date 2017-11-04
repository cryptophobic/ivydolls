<?php use yii\helpers\Html; ?>
<?php $this->beginPage() ?>
<?php


    $this->registerCssFile('/bootstrap/css/bootstrap.min.css',['position' => static::POS_HEAD]);
    $this->registerCssFile('/calendar/vendor/bower/bootstrap-calendar/css/calendar.min.css',['position' => static::POS_HEAD]);
    $this->registerJsFile('/js/jquery-3.2.1.min.js',['position' => static::POS_HEAD]);
    $this->registerJsFile('/bootstrap/js/bootstrap.min.js',['position' => static::POS_HEAD]);
    $this->registerJsFile('/calendar/vendor/bower/underscore/underscore.js',['position' => static::POS_HEAD]);
    $this->registerJsFile('/calendar/vendor/bower/bootstrap-calendar/js/calendar.js',['position' => static::POS_HEAD]);
    $this->registerJsFile('/calendar/vendor/bower/bootstrap-calendar/js/language/ru-RU.js',['position' => static::POS_HEAD]);
    $this->registerCssFile('/css/font-awesome-4.7.0/css/font-awesome.min.css',['position' => static::POS_HEAD]);

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
        <?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>