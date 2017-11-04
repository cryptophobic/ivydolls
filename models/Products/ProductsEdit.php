<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Products;

use app\models\BO\ResampleImages;
use app\models\Category\CategoryPack;
use app\models\ProductsOptions\ProductsOptions;
use app\models\ProductsOptions\ProductsOptionsCollection;
use app\models\ProductsOptions\ProductsOptionsDelete;
use app\models\ProductsOptions\ProductsOptionsEdit;
use app\models\ProductsSpecs\ProductsSpecsCollection;
use app\models\ProductsSpecs\ProductsSpecsDelete;
use app\models\ProductsSpecs\ProductsSpecsEdit;
use Yii;

class ProductsEdit
{
    private $_productData = [];

    /**
     * CategoryEdit constructor.
     * @param $productData
     */
    public function __construct($productData)
    {
        //TODO: validate
        $this->_productData = $productData;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $product = new ProductsPack();
        $product->setBatch($this->_productData);
        $product->addItem();
        $result = $product->flush();
        $result->last();
        $productId = $result->product_id;
        $this->saveOptions($productId);
        $this->saveSpecs($productId);
        return $result->product_id;
    }

    private function saveOptions($productId)
    {
        if (!empty($this->_productData['products_options'])) {
            $oldOptions = new ProductsOptionsCollection();
            $oldOptions->setProductIds($productId);
            $optionsDelete = new ProductsOptionsDelete($oldOptions->getIds());
            $optionsDelete->delete();
            $options = new ProductsOptionsEdit($this->_productData['products_options'], $productId);
            $options->save();
        }
    }

    private function saveSpecs($productId)
    {
        if (!empty($this->_productData['products_specs'])) {
            $oldSpecs = new ProductsSpecsCollection();
            $oldSpecs->setProductIds($productId);
            $specsDelete = new ProductsSpecsDelete($oldSpecs->getIds());
            $specsDelete->delete();
            $specs = new ProductsSpecsEdit($this->_productData['products_specs'], $productId);
            $specs->save();
        }
    }
}