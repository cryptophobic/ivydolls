<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\ProductsOptions;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Options\Options;
use app\models\Products\Products;

/**
 * @method ProductsOptionsPack getItemsByIds($ids)
 * @method $this loadOptionInfo (bool $set = true)
 *
 * @var ProductsOptionsPack _items
 *
 */
class ProductsOptions extends ModelAccessor
{
    protected function initialize()
    {
        $this->_items = new ProductsOptionsPack();
    }

    protected $_options = [
        '_loadOptionInfo' => false,
    ];

    protected function _loadOptionInfo()
    {
        if (!empty($this->_ids))
        {
            $options = new Options();
            $options->loadRestrictedValues();
            $items = $options->getItemsByIds($this->_ids['option_id']);
            $this->_items->mergeDataPack($items, 'options');

            return true;
        } else {
            return false;
        }
    }

}