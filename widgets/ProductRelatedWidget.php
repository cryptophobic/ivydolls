<?php

namespace app\widgets;

use app\models\Products\ProductPresentation;
use yii\base\Widget;
use yii\helpers\Html;


/**
 * Created by PhpStorm.
 * User: dima
 * Date: 3/22/2017
 * Time: 1:48 AM
 */
class ProductRelatedWidget extends Widget
{
    /**
     * @var ProductPresentation
     */
    public $productPresentation = null;

    public function run()
    {
        return $this->render('productRelated', ['productPresentation' => $this->productPresentation]);
    }
}