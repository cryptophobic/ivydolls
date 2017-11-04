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
$this->registerCssFile('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', ['position' => static::POS_HEAD]);
$this->registerCssFile('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css', ['position' => static::POS_HEAD]);
$this->registerCssFile('/css/font-awesome-4.7.0/css/font-awesome.min.css', ['position' => static::POS_HEAD]);
$this->registerJsFile('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', ['position' => static::POS_HEAD]);
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
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= yii::$app->params['company'] ?></h4>
            </div>
            <div class="modal-body">
                <!--                <p>Приносим свои извинения, сайт работает в экспериментальном режиме</p>
                                <p>По вопросам приобретения кукол, тресс, лаков и спреев и ещё кое-чего обращаться</p>
                                <p>тел: <a href="Tel:+380980569963"><span class="glyphicon glyphicon-earphone"></span>+38 (098)
                                        056-99-63</a></p>
                                <p>email: <a href="mailto:ivydollstudio@gmail.com">ivydollstudio@gmail.com</a></p>
                                <p><i class="fa fa-vk" aria-hidden="true"></i>&nbsp;<a href="https://www.facebook.com/IvydollStudio/">facebook.com/IvydollStudio/</a></p>
                                <p><i class="fa fa-facebook" aria-hidden="true"></i>&nbsp;<a href="https://vk.com/ivydollstudio">vk.com/ivydollstudio</a></p>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="main" class="wrap container-fluid">
    <nav class="navbar navbar-inverse navbar-fixed-top topnav">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/" class="navbar-brand"><?= yii::$app->params['company'] ?></a>
            </div>
            <ul class="nav navbar-nav">
                <form class="navbar-form" action="/start/load-products" role="search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Найти" name="keyword">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </ul>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <!--<li><a class="scroll-link" href="#shipping"><span class="glyphicon shipping fa fa-truck">
                                </span>&nbsp;Доставка</a></li>
                    <li>
                    <li><a href="Tel:+380980569963"><span class="glyphicon glyphicon-earphone"></span>+38 (098)
                            056-99-63</a></li>-->
                    <li><a href="#" class="modal-load" data-toggle="modal" data-target="#myModal"
                           data-content="/static/contact-page"><span class="glyphicon glyphicon-earphone"></span>Контакты</a></li>
                    <? if (false) {
                        ?>
                        <li>
                            <span class="menuIcon glyphicon glyphicon-shopping-cart my-cart-icon">
                                <span class="badge badge-notify my-cart-badge"></span>
                            </span>
                        </li>
                    <li><a href="#"><span class="glyphicon glyphicon-user" title="Зарегистрироваться"></span> </a></li>
                    <? } ?>
                    <li><a href="#" class="modal-load" data-toggle="modal" data-target="#myModal"
                           data-content="/authorize/login-form"><span class="glyphicon glyphicon-log-in"></span>
                            Войти</a></li>
                </ul>
            </div>
        </div>
    </nav>

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
