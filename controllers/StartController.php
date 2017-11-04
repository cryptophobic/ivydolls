<?php

namespace app\controllers;

use app\models\Category\CategoryCollection;
use app\models\ObjFactory;
use app\models\Products\Products;
use app\models\Products\ProductsCollection;
use Yii;

class StartController extends FOController
{
    public function actionIndex($scrollUrl = '')
    {
        $categoriesCollection = new CategoryCollection();
        $categories = $categoriesCollection->getItems();

        $scrollUrl = str_replace('offset', '', $scrollUrl);

        return $this->render('index',
            [
                'categories' => $categories,
                'scrollUrl' => $scrollUrl
            ]
        );
    }

    public function actionLoadProductsJson($keyword)
    {
        $collection = new ProductsCollection();
        $collection->setKeyWord($keyword);
        $dolls = $collection->getItems();
        return json_encode(['body' => $this->render('suggest', ['products' => $dolls])]);
    }

    public function actionLoadProducts($categoryId = 112, $height = 0, $offset = 0, $keyword = '', $productIds = "")
    {
        if (Yii::$app->request->isAjax) {
            $scrollUrl = '';
            if (!empty($productIds)) {
                $products = new Products();
                $productIds = explode(",", $productIds);
                $dolls = $products->loadImages()->getItemsByIds(['product_id' => $productIds]);
            } else {
                $collection = new ProductsCollection();

                if (!empty($categoryId)) {
                    $collection->setCategoryId($categoryId);
                }

                if (!empty($height)) {
                    $collection->setHeight($height);
                }

                if (!empty($keyword)) {
                    $collection->setKeyWord($keyword);
                }

                $dolls = $collection->getItems($offset);
                $newOffset = $collection->getOffset();

                $scrollUrl = ObjFactory::urlManager()->createUrl([
                    Yii::$app->controller->id . '/' . Yii::$app->controller->action->id,
                    'categoryId' => $categoryId,
                    'keyword' => $keyword,
                    'height' => $height,
                    'offset' => $newOffset]);
            }
            return json_encode([
                'body' => $this->render('indexAjax', ['items' => $dolls]),
                'scrollUrl' => $scrollUrl
            ]);
        } else {
            return $this->actionIndex(ObjFactory::request()->url);
        }
    }

}
