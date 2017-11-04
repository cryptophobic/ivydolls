<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:30 PM
 */

namespace app\models\OptionsRestrictedValues;

class OptionsRestrictedValuesEdit
{
    private $_optionsRestrictedValues = [];

    /**
     * @param $optionsRestrictedValues
     */
    public function __construct($optionsRestrictedValues)
    {
        $this->_optionsRestrictedValues = $optionsRestrictedValues;
    }

    public function save()
    {
        if (empty($this->_optionsRestrictedValues)) return false;
        $optionsRestrictedValuesPack = new OptionsRestrictedValuesPack();

        foreach($this->_optionsRestrictedValues as $value)
        {
            $optionsRestrictedValuesPack->newItem()->setBatch($value);
            $optionsRestrictedValuesPack->addItem();
        }
        $optionsRestrictedValuesPack->flush();
        return true;
    }
}