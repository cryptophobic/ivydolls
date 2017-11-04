<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:41 PM
 */

namespace app\models\ProductsOptions;

class ProductsOptionsEdit
{
    private $_productsOptions = [];
    private $_productId = 0;

    /**
     * @param $productsOptions
     * @param $productId
     */
    public function __construct($productsOptions, $productId)
    {
        $this->_productsOptions = $productsOptions;
        $this->_productId = $productId;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (empty($this->_productsOptions)) return false;
        $productsOptionsPack = new ProductsOptionsPack();

        foreach ($this->_productsOptions as $option)
        {
            $productsOptionsPack->newItem()->setBatch($option);
            $productsOptionsPack->product_id = $this->_productId;
            $productsOptionsPack->addItem();
        }
        $productsOptionsPack->flush();
        return true;
    }
}