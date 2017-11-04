<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:41 PM
 */

namespace app\models\ProductsRelated;

class ProductsRelatedEdit
{
    private $_productsRelated = [];
    private $_productId = 0;

    /**
     * @param $productsRelated
     * @param $productId
     */
    public function __construct($productsRelated, $productId)
    {
        $this->_productsRelated = $productsRelated;
        $this->_productId = $productId;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (empty($this->_productsRelated)) return false;
        $productsRelatedPack = new ProductsRelatedPack();

        foreach ($this->_productsRelated as $related)
        {
            $productsRelatedPack->setBatch($related);
            $productsRelatedPack->product_id = $this->_productId;
            $productsRelatedPack->addItem();
        }
        $productsRelatedPack->flush();
        return true;
    }
}