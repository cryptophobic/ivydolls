<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\Specs;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\SpecsRestrictedValues\SpecsRestrictedValuesPack;


/**
 * Class SpecsPack
 *
 * @property integer spec_id
 * @property string original_name
 * @property string name
 * @property integer category_id
 * @property string description
 * @property SpecsRestrictedValuesPack specs_restricted_values
 * @method SpecsPack flush
 */
class SpecsPack extends ItemsPack
{
    protected function isProperType($name, $value)
    {
        if($name === 'specs_restricted_values' && $value instanceof SpecsRestrictedValuesPack){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['specs_restricted_values'] = new SpecsRestrictedValuesPack();
        return parent::initializeReferences();
    }


    protected static $_primaryKey = ['spec_id'];
    protected static $_tableName = 'specs';


    protected $_fields = [
        'spec_id' => null,
        'name' => null,
        'original_name' => null,
        'category_id' => null,
        'description' => null
    ];

    protected $_references = [
        'specs_restricted_values' => null
    ];

    protected $_mandatory = [
        'category_id' => true,
        'name' => true
    ];
}