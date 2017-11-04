<?php

namespace app\controllers;

use app\models\Products\ProductPresentation;
use app\models\Products\Products;

class DollsController extends FOController
{
    /**
     * @param $dollName
     * @return string
     */
    public function actionIndex($productId)
    {
        $dolls = new Products();
        $product = $dolls->getItemsByIds(['product_id' => $productId]);


        return $this->render('index', [
            'productPresentation' => new ProductPresentation($product->product_id)
        ]);
    }
}
