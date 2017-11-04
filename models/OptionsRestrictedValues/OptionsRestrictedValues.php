<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\OptionsRestrictedValues;
use app\models\Abstractive\Complex\ModelAccessor;

/**
 * @method OptionsRestrictedValuesPack getItemsByIds ($ids)
 * @var OptionsRestrictedValuesPack _items
 */
class OptionsRestrictedValues extends ModelAccessor
{
    protected function initialize()
    {
        $this->_items = new OptionsRestrictedValuesPack();
    }
}