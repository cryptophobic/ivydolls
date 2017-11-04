<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/30/2017
 * Time: 9:47 AM
 */

namespace app\modules\admin\controllers;


use app\models\Products\ProductsCollection;
use app\models\ProductsSpecs\ProductsSpecsCollection;
use app\models\ProductsSpecs\ProductsSpecsPack;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionAdd()
    {
        $pack = new ProductsSpecsPack();
        $pack->spec_id = 85;
        $pack->product_id = 1;
        $pack->value = 'psvalue';
        $pack->addItem();
        $pack->flush();
    }

    public function actionGet()
    {
        $pack = new ProductsCollection();
        $pack->getModel()->loadSpecs()->loadImages()->loadBrandInfo()->loadOptions();
        $result = $pack->getItems();

        for ($result->first();$result->current();$result->next())
        {
            var_dump($result->fields());
        }
        //var_dump($pack->getItems());
    }

}