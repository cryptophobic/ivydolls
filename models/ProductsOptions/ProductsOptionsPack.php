<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\ProductsOptions;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Options\OptionsPack;

/**
 * Class ProductsOptionsPack
 *
 * @property integer option_id
 * @property integer product_id
 * @property integer options_restricted_values_id
 * @property string price
 * @property OptionsPack options
 * @method ProductsOptionsPack flush
 */
class ProductsOptionsPack extends ItemsPack
{
    /**
     * @param $name
     * @param $value
     * @return bool
     */
    protected function isProperType($name, $value)
    {
        if($name === 'options' && $value instanceof OptionsPack)
        {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['options'] = new OptionsPack();
        return parent::initializeReferences();
    }

    protected $_fields = [
        'option_id' => null,
        'product_id' => null,
        'options_restricted_values_id' => 0,
        'price' => null,
    ];

    protected static $_primaryKey = ['option_id', 'product_id', 'options_restricted_values_id'];

    protected static $_tableName = 'products_options';

    protected $_references = [
        'options' => null,
    ];

    protected $_mandatory = [
        'product_id' => true,
        'option_id' => true,
    ];

}