<?php

$this->registerCssFile('/css/cart/reset.css');
$this->registerCssFile('/css/cart/style.css');
$this->registerJsFile('/js/cart/main.js');

?>

<div class="cd-cart-container empty">
    <a href="#0" class="cd-cart-trigger">
        Cart
        <ul class="count"> <!-- cart items count -->
            <li>0</li>
            <li>0</li>
        </ul> <!-- .count -->
    </a>

    <div class="cd-cart">
        <div class="wrapper">
            <header>
                <h2>Cart</h2>
                <span class="undo">Ттовар удалён. <a href="#0">Отменить</a></span>
            </header>

            <div class="body">
                <ul>
                    <!-- products added to the cart will be inserted here using JavaScript -->
                </ul>
            </div>

            <footer>
                <a href="#0" class="checkout btn"><em>Всего - <span>0</span> грн</em></a>
            </footer>
        </div>
    </div> <!-- .cd-cart -->
</div> <!-- cd-cart-container -->