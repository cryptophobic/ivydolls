<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:16 PM
 */

namespace app\models\Options;

use app\models\OptionsRestrictedValues\OptionsRestrictedValuesEdit;

class OptionsEdit
{
    private $_optionsData = [];

    public function __construct($optionsData)
    {
        //TODO: validate
        $this->_optionsData = $optionsData;
    }

    public function save()
    {
        if (empty($this->_optionsData)) return false;
        $optionsPack = new OptionsPack();

        foreach ($this->_optionsData as $option) {
            $optionsPack->newItem()->setBatch($option);
            $optionId = $optionsPack->option_id;
            $optionsPack->addItem();
            if(!empty($option['options_restricted_values'])) {
                if (empty($optionId)) {
                    $result = $optionsPack->flush();
                    $result->last();
                }
                $this->saveRestrictedValues($option['options_restricted_values']);
            }
        }
        $optionsPack->flush();
        return true;
    }

    private function saveRestrictedValues($restrictedValues)
    {
        $optionsRestrictedValues = new OptionsRestrictedValuesEdit($restrictedValues);
        $optionsRestrictedValues->save();
    }
}