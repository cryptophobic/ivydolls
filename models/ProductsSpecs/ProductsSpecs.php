<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\ProductsSpecs;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Specs\Specs;
use app\models\Specs\SpecsCollection;

/**
 * @method ProductsSpecsPack getItemsByIds($ids)
 * @method $this loadSpecInfo (bool $set = true)
 *
 * @var ProductsSpecsPack _items
 *
 * Class Dolls
 * @package app\models\ProductCollection
 */
class ProductsSpecs extends ModelAccessor
{
    protected function initialize()
    {
        $this->_items = new ProductsSpecsPack();
    }

    protected $_options = [
        '_loadSpecInfo' => false,
    ];

    protected function _loadSpecInfo()
    {
        if (!empty($this->_ids))
        {
            $specs = new Specs();
            $specs->loadRestrictedValues();
            $items = $specs->getItemsByIds(['spec_id' => $this->_ids['spec_id']]);
            $this->_items->mergeDataPack($items, 'specs');
            return true;
        } else {
            return false;
        }
    }

}