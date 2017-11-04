<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;


/**
 * Created by PhpStorm.
 * User: dima
 * Date: 3/22/2017
 * Time: 1:48 AM
 */
class CartModalWidget extends Widget
{
    public function run()
    {
        return $this->render('cartModal');
    }
}