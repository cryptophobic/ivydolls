<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Brands;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Products\ProductsCollection;
use app\models\Products\ProductsPack;

/**
 * @method BrandsPack getItemsByIds ($ids)
 * @var BrandsPack _items
 */
class Brands extends ModelAccessor
{
    protected function initialize()
    {
        $this->_items = new BrandsPack();
    }

    /**
     * load products options
     *
     * @return bool
     */
    protected function _loadProducts()
    {
        if (!empty($this->_ids))
        {
            $productsCollection = new ProductsCollection();
            $productsCollection->getModel()->loadCategoryInfo();
            $productsCollection->setBrandIds($this->_ids);
            $products = new ProductsPack();

            for ($productsPack = $productsCollection->getItems();
                 $productsPack->current();
                 $productsPack = $productsCollection->getNext())
            {
                $products->merge($productsPack);
            }

            $this->_mergeDataPack($products, ProductsPack::getTableName());
            return true;
        } else {
            return false;
        }
    }
}