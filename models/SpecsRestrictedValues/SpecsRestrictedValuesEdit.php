<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:46 PM
 */

namespace app\models\SpecsRestrictedValues;

class SpecsRestrictedValuesEdit
{
    private $_restrictedValues = [];


    /**
     * @param $restrictedValues
     */
    public function __construct($restrictedValues)
    {
        $this->_restrictedValues = $restrictedValues;
    }

    /**
     * @return bool
     */
    public function save()
    {
        $specsRestrictedValuesPack = new SpecsRestrictedValuesPack();
        foreach ($this->_restrictedValues as $value)
        {
            $specsRestrictedValuesPack->newItem()->setBatch($value);
            $specsRestrictedValuesPack->addItem();
        }
        $specsRestrictedValuesPack->flush();
        return true;
    }
}