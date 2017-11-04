<?php

use app\models\Feedback\FeedbackPack;


$this->registerCssFile('/bootstrap-select/css/bootstrap-select.min.css',['position' => static::POS_END]);
$this->registerJsFile('/bootstrap-select/js/bootstrap-select.min.js',['position' => static::POS_END]);

/**
 * @var FeedbackPack $items
 * @var string $keyword
 * @var integer $new
 * @var integer $newOffset
 */

?>

<form method="post" class="submitWarn" action="/admin/feedback/index">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>"/>

    <div class="container-fluid">
        <div class="col-sm-2">
            <label for="new">Непрочитанные</label>
            <input type="checkbox" id="new" class="submitiative" data-action="/admin/feedback/index" name="new" value="1" <?php if ($new>0){?>checked="checked"<?php }?> >
        </div>

        <div class="col-sm-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Найти" name="keyword" value="<?= $keyword ?>">
                <div class="input-group-btn">
                    <button class="btn btn-default" data-action="/admin/feedback/index" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <button type="submit" name="action" value="read" data-action="/admin/feedback/save" class="btn btn-primary">
                Снять пометку
            </button>
        </div>
        <div class="col-sm-2">
            <button type="submit" name="action" value="unread" data-action="/admin/feedback/save" class="btn btn-primary">
                Пометить
            </button>
        </div>
        <div class="col-sm-2">
            <button type="submit" data-action="/admin/feedback/delete" id="deleteFeedback" class="btn btn-primary">
                Удалить
            </button>
        </div>


    </div>
    <div class="container-fluid">

        <table class="table">
            <thead>
            <tr>
                <th>
                    <nobr><input type="checkbox" class="batch" data-target="feedbackCheck" value=""></nobr>
                </th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Email</th>
                <th>Ip</th>
                <th>referrer</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>

            <?php
            for ($items->first(); $items->current(); $items->next()) {
                ?>

                <tr <?php if($items->new == 1){?>class="active"<?php } ?> data-url="/admin/feedback/feedback?feedbackId=<?= $items->feedback_id ?>">
                    <td class="col-sm-1">
                        <input type="checkbox" class="feedbackCheck" name="mark[<?= $items->feedback_id ?>]" value="1"/>
                    </td>
                    <td class="col-sm-2 loadable"><?= $items->name ?></td>
                    <td class="col-sm-2 loadable"><?= $items->phone ?></td>
                    <td class="col-sm-2 loadable"><?= $items->email ?></td>
                    <td class="col-sm-2 loadable"><?= $items->ip ?></td>
                    <td class="col-sm-1"><a target="_blank" href="<?= $items->referrer ?>">link</a></td>
                    <td class="col-sm-2 loadable"><?= $items->updated ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</form>