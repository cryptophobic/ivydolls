<?php

use app\widgets;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

$this->registerCssFile('/css/test.css');
//$this->registerCssFile('/css/sideMenu.css');


?>

<div id="main" class="wrap container-fluid">
    <nav class="navbar navbar-inverse navbar-fixed-top topnav">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand scroll-link" href="#myCarousel">Лого или название</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li><a class="scroll-link" href="#assortment">О нас, курсы и проч</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a class="scroll-link" href="#shipping"><span class="glyphicon shipping fa fa-truck">
                                </span>&nbsp;Доставка</a></li>
                    <li>
                    <li><a href="Tel:+380980569963"><span class="glyphicon glyphicon-earphone"></span>+38 (098)
                            056-99-63</a></li>
                    <li>
                            <span class="menuIcon glyphicon glyphicon-shopping-cart my-cart-icon">
                                <span class="badge badge-notify my-cart-badge"></span>
                            </span>
                    </li>
                    <li><a href="#"><span class="glyphicon glyphicon-user" title="Зарегистрироваться"></span> </a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Войти</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <div id="calendar"></div>

</div>
<script type="text/javascript">

    $(document).ready(function () {

        var calendar = $("#calendar").calendar(
            {
                events_source: '/test/events',
                tmpl_path: "/calendar/vendor/bower/bootstrap-calendar/tmpls/",
                language: 'ru-RU'
            });
    });

</script>

