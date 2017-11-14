<?php

$this->registerJsFile('/widgets/js/carousel.js');
$this->registerCssFile('/widgets/css/carousel.css');

?>

<div id="myCarousel" class="row carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
        <li data-target="#myCarousel" data-slide-to="3"></li>
        <li data-target="#myCarousel" data-slide-to="4"></li>
        <li data-target="#myCarousel" data-slide-to="5"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">

        <div class="carousel-item active">
            <img data-href="/dolls?productId=118#assortment" src="/images/carousel/FuSangAncient.jpg" alt="Flower">
            <div class="carousel-caption">
                <h3>Fu Sang (Ancient)</h3>
                <p></p>
            </div>
        </div>

        <div class="carousel-item">
            <img data-href="/dolls?productId=119#assortment" src="/images/carousel/FuSangModern.jpg" alt="Flower">
            <div class="carousel-caption">
                <h3>Fu Sang (Modern)</h3>
                <p></p>
            </div>
        </div>

        <div class="carousel-item">
            <img data-href="/start?productIds=121,120#assortment" src="/images/carousel/Mirror.jpg" alt="Flower">
            <div class="carousel-caption">
                <h3>Live in Mirror</h3>
                <p></p>
            </div>
        </div>

        <div class="carousel-item">
            <img data-href="/dolls?productId=186#assortment" src="/images/carousel/Chaos.jpg" alt="Chania">
            <div class="carousel-caption">
                <h3>Chaos</h3>
                <p></p>
            </div>
        </div>

        <div class="carousel-item">
            <img data-href="/dolls?productId=191#assortment" src="/images/carousel/ChaosBeast.jpg" alt="Chania">
            <div class="carousel-caption">
                <h3>Chaos (Beast version)</h3>
                <p></p>
            </div>
        </div>

        <div class="carousel-item">
            <img data-href="/start?productIds=128,199#assortment" src="/images/carousel/MarkusTaowu.jpg"
                 alt="Flower">
            <div class="carousel-caption">
                <h3>Marcus & Taowu</h3>
                <p></p>
            </div>
        </div>

    </div>

    <!-- Left and right controls -->

    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next" href="#myCarousel" data-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>

</div>


