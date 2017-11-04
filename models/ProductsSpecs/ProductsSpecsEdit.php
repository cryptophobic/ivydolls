<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:41 PM
 */

namespace app\models\ProductsSpecs;

class ProductsSpecsEdit
{
    private $_productSpecs = [];
    private $_productId = 0;

    /**
     * @param $productsSpecs
     * @param $productId
     * @param $specId
     */
    public function __construct($productsSpecs, $productId)
    {
        $this->_productSpecs = $productsSpecs;
        $this->_productId = $productId;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (empty($this->_productSpecs)) return false;
        $productSpecsPack = new ProductsSpecsPack();

        foreach ($this->_productSpecs as $spec)
        {
            $productSpecsPack->newItem()->setBatch($spec);
            $productSpecsPack->product_id = $this->_productId;
            $productSpecsPack->addItem();
        }
        $productSpecsPack->flush();
        return true;
    }
}