<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\ProductsRelated;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Products\Products;

/**
 * @method ProductsRelatedPack getItemsByIds($ids)
 * @method $this loadProductInfo (bool $set = true)
 *
 * @var ProductsRelatedPack _items
 *
 * Class Dolls
  */
class ProductsRelated extends ModelAccessor
{
    protected function initialize()
    {
        $this->_items = new ProductsRelatedPack();
    }

    protected $_options = [
        '_loadProductInfo' => false,
    ];

    protected function _loadProductInfo()
    {
        if (!empty($this->_ids))
        {
            $products = new Products();
            $products->loadImages();
            $items = $products->getItemsByIds(['product_id' => $this->_ids['product_related_id']]);
            $this->_items->mergeDataPack($items, 'products', ['product_id' => 'product_related_id']);

            return true;
        } else {
            return false;
        }
    }

}