<?php use yii\helpers\Html; ?>
<?php $this->beginPage() ?>

<?php
/*$this->registerCssFile('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css');

$this->registerJsFile('https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js',['position' => static::POS_HEAD]);
$this->registerJsFile('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js',
    [
        'position' => static::POS_HEAD
    ]);*/

$this->registerJsFile('/js/jquery-3.2.0.min.js', ['position' => static::POS_HEAD]);
$this->registerJsFile('/js/Utils.js', ['position' => static::POS_HEAD]);
$this->registerCssFile('/bootstrap4/css/bootstrap.min.css', ['position' => static::POS_HEAD]);
$this->registerCssFile('/css/font-awesome-4.7.0/css/font-awesome.min.css', ['position' => static::POS_HEAD]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js', ['position' => static::POS_HEAD]);
$this->registerJsFile('/bootstrap4/js/bootstrap.min.js', ['position' => static::POS_HEAD]);
$this->registerCssFile('/css/site.css');
$this->registerJsFile('/js/site.js');

?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= /*Html::encode($this->title)*/
        yii::$app->params['company'] ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">><?= yii::$app->params['company'] ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<nav class="navbar navbar-expand-lg navbar-expand-md navbar-dark fixed-top bg-dark">

    <div class="row">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent,companyName" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>


        <span id="collapse navbar-collapse">
            <a  href="/" class="navbar-brand nav-link"><?= yii::$app->params['company'] ?></a>
        </span>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#searchInput"
                aria-controls="searchInput" aria-expanded="true" aria-label="Toggle navigation">
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>


    </div>

    <div class="collapse navbar-collapse" id="searchInput">
        <form action="/start" class="mx-2 my-auto d-inline">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Найти">
                <span class="input-group-btn">
                        <button class="btn btn-outline-secondary" type="button">GO</button>
                    </span>
            </div>
        </form>
    </div>


    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a href="#" class="modal-load nav-link" data-toggle="modal" data-target="#myModal"
                   data-content="/static/contact-page"><span
                    <i class="fa fa-phone" aria-hidden="true"></i>&nbsp;Контакты</a></li>
            <li class="nav-item">
                <a href="#" class="modal-load  nav-link" data-toggle="modal" data-target="#myModal"
                   data-content="/authorize/login-form"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;
                    Войти</a></li>

            <? if (false) {
                ?>
                <li class="nav-item"><a class="scroll-link" href="#shipping"><span class="glyphicon shipping fa fa-truck"></span>&nbsp;Доставка</a></li>
                <li class="nav-item"><a href="Tel:+380980569963"><span class="glyphicon glyphicon-earphone"></span>+38 (098) 056-99-63</a></li>
                <li class="nav-item"><span class="menuIcon glyphicon glyphicon-shopping-cart my-cart-icon"><span class="badge badge-notify my-cart-badge"></span></span></li>
            <? } ?>

        </ul>
    </div>
</nav>

<div id="main" class="wrap container-fluid">


    <?= $content ?>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= yii::$app->params['company'] . ' ' . date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
