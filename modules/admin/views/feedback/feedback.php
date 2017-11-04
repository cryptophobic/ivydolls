<?php

use app\models\Feedback\FeedbackPack;

/**
 * @type FeedbackPack $feedback
 */


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12"><h2><?= $feedback->name ?></h2></div>
    </div>
    <div class="row">
        <label>Телефон:</label>
        <p><?= $feedback->phone ?></p>
    </div>
    <div class="row">
        <label>Email:</label>
        <p><?= $feedback->email ?></p>
    </div>
    <div class="row">
        <label>ip:</label>
        <p><?= $feedback->ip ?></p>
    </div>
    <div class="row">
        <label>Referrer:</label>
        <a href="<?= $feedback->referrer ?>"><?= $feedback->referrer ?></a>
    </div>
    <div class="row">
        <label>Сообщение:</label>
        <p><?= $feedback->message ?></p>
    </div>
</div>