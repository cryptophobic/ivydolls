<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\SpecsRestrictedValues;
use app\models\Abstractive\Complex\ItemsPack;


/**
 * Class SpecsRestrictedValuesPack
 *
 * @property integer spec_id
 * @property integer specs_restricted_values_id
 * @property string value
 */
class SpecsRestrictedValuesPack extends ItemsPack
{
    protected $_fields = [
        'spec_id' => null,
        'specs_restricted_values_id' => null,
        'value' => null,
    ];

    protected static $_primaryKey = ['specs_restricted_values_id'];

    protected static $_tableName = 'specs_restricted_values';

    protected $_mandatory = [
        'spec_id' => true,
        'value' => true
    ];
}
