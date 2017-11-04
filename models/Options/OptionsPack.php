<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:26 PM
 */

namespace app\models\Options;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesPack;

/**
 * Class OptionsPack
 *
 * @property integer option_id
 * @property string name
 * @property string original_name
 * @property float price
 * @property integer category_id
 * @property OptionsRestrictedValuesPack options_restricted_values
 * @method OptionsPack flush
 */
class OptionsPack extends ItemsPack
{

    protected static $_primaryKey = ['option_id'];

    protected static $_tableName = 'options';

    protected function isProperType($name, $value)
    {
        if($name === 'options_restricted_values' && $value instanceof OptionsRestrictedValuesPack){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['options_restricted_values'] = new OptionsRestrictedValuesPack();
        return parent::initializeReferences();
    }

    protected $_fields = [
        'option_id' => null,
        'description' => null,
        'name' => null,
        'original_name' => null,
        'price' => null,
        'category_id' => null,
    ];

    protected $_references = [
        'options_restricted_values' => null
    ];

    protected $_mandatory = [
        'name' => true,
        'category_id' => true,
    ];
}