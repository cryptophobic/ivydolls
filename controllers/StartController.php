<?php

namespace app\controllers;

use app\models\Category\Categories;
use app\models\Category\CategoryCollection;
use app\models\ObjFactory;
use app\models\Products\Products;
use app\models\Products\ProductsCollection;
use app\models\Products\ProductsSearch;
use Yii;

class StartController extends FOController
{

    private function _getProductPack($array)
    {
        $productsSearch = new ProductsSearch();
        $productsSearch->_categoryId = empty($array['categoryId'])?"112":$array['categoryId'];
        $productsSearch->_height = empty($array['height'])?"0":$array['height'];
        $productsSearch->_offset = empty($array['offset'])?"0":$array['offset'];
        $productsSearch->_brandIds = empty($array['brandId'])?"0":$array['brandId'];
        $productsSearch->_keyword = empty($array['keyword'])?"":$array['keyword'];
        $productsSearch->_productIds = empty($array['productIds'])?[]:explode(",", $array['productIds']);
        return $productsSearch;
    }

    public function actionIndex()
    {
        $categoryId = empty($_REQUEST['categoryId'])?"112":$_REQUEST['categoryId'];

        $category = new Categories();
        $categoryPack = $category->getItemsByIds(['category_id' => [$categoryId]]);

        $categoriesCollection = new CategoryCollection();
        $categoriesCollection->setParentCategoryIds(-1);
        $categoriesCollection->getModel()->loadCategoryHierarchy();
        $categories = $categoriesCollection->getAll();

        $productsSearch = $this->_getProductPack($_REQUEST);

        return $this->render('index',
            [
                'category' => $categoryPack,
                'categories' => $categories,
                //'count' => $productsSearch->getCount(),
                'productsSearch' => $productsSearch,
                'content' => $this->renderEmpty('indexAjax', ['items' => $productsSearch->getProducts()])
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

    public function actionLoadProducts()
    {
        if (Yii::$app->request->isAjax) {

            $productsSearch = $this->_getProductPack($_REQUEST);
            return json_encode([
                'body' => $this->render('indexAjax', ['items' => $productsSearch->getProducts()]),
                'scrollUrl' => $productsSearch->getScrollUrl(),
            ]);
        } else {
            return $this->actionIndex();
        }
    }

}
