<?php
?>

<h4 class="caption text-warning">
    Ошибка, попробуйте позже, пожалуйста или свяжитесь с нами
</h4>
<div class="row">
    <div class="col-sm-2">
        <label for="complaintEmail">Email:</label>
    </div>
    <div class="col-sm-2">
        <a id="complaintEmail" href="mailto:<?= yii::$app->params['email'] ?>">
            <?= yii::$app->params['email'] ?>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-2">
        <label for="complaintEmail">Телефон:</label>
    </div>
    <div class="col-sm-2">
        <a id="complaintEmail" href="tel:<?= yii::$app->params['phone'] ?>">
            <?= yii::$app->params['phone'] ?>
        </a>
    </div>
</div>