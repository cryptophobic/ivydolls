<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Products;

use app\models\Abstractive\Simple\ItemsDelete;
use app\models\ProductsImages\ProductsImagesCollection;
use app\models\ProductsImages\ProductsImagesDelete;
use app\models\ProductsOptions\ProductsOptionsCollection;
use app\models\ProductsOptions\ProductsOptionsDelete;
use app\models\ProductsRelated\ProductsRelatedCollection;
use app\models\ProductsRelated\ProductsRelatedDelete;
use app\models\ProductsSpecs\ProductsSpecsCollection;
use app\models\ProductsSpecs\ProductsSpecsDelete;

class ProductsDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = ProductsPack::getTableName();
        $this->_idName = ProductsPack::getPrimaryKey();
        parent::__construct($ids);
    }

    private function deleteProductsSpecs()
    {
        $productsSpecsCollection = new ProductsSpecsCollection();
        $productsSpecsCollection->setProductIds($this->_ids['product_id']);
        $productsSpecsCollection->setLimit(100);
        while ($ids = $productsSpecsCollection->getIds()) {
            $productsSpecsDelete = new ProductsSpecsDelete($ids);
            $productsSpecsDelete->delete();
        }
    }

    private function deleteProductsImages()
    {
        $productsImagesCollection = new ProductsImagesCollection();
        $productsImagesCollection->setProductIds($this->_ids['product_id']);
        $productsImagesCollection->setLimit(100);
        while ($ids = $productsImagesCollection->getIds()) {
            $productsImagesDelete = new ProductsImagesDelete($ids);
            $productsImagesDelete->delete();
        }
    }

    private function deleteProductsOptions()
    {
        $productsOptionsCollection = new ProductsOptionsCollection();
        $productsOptionsCollection->setProductIds($this->_ids['product_id']);
        $productsOptionsCollection->setLimit(100);
        while ($ids = $productsOptionsCollection->getIds()) {
            $productsOptionsDelete = new ProductsOptionsDelete($ids);
            $productsOptionsDelete->delete();
        }
    }

    private function deleteProductsRelated()
    {
        $productsRelatedCollection = new ProductsRelatedCollection();
        $productsRelatedCollection->setProductIds($this->_ids['product_id']);
        $productsRelatedCollection->setLimit(100);
        while ($ids = $productsRelatedCollection->getIds()) {
            $productsRelatedDelete = new ProductsRelatedDelete($ids);
            $productsRelatedDelete->delete();
        }
    }

    public function delete()
    {
        $this->deleteProductsSpecs();
        $this->deleteProductsOptions();
        $this->deleteProductsImages();
        $this->deleteProductsRelated();
        parent::delete();
    }

}