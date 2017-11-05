<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\OptionsRestrictedValues;
use app\models\Abstractive\Complex\ItemsPack;


/**
 * Class OptionsRestrictedValuesPack
 *
 * @property integer $options_restricted_values_id
 * @property string $value
 * @property string $original_value
 * @property int $option_id
 * @property float $price
 * @method OptionsRestrictedValuesPack flush
 */
class OptionsRestrictedValuesPack extends ItemsPack
{

    protected $_fields = [
        'option_id' => null,
        'options_restricted_values_id' => null,
        'value' => null,
        'original_value' => null,
        'price' => null
    ];
    protected static $_primaryKey = ['options_restricted_values_id'];

    protected static $_tableName = 'options_restricted_values';

    protected $_mandatory = [
        'option_id' => true,
        'value' => true
    ];
}
