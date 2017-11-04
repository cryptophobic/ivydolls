<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:41 PM
 */

namespace app\models\Specs;

use app\models\SpecsRestrictedValues\SpecsRestrictedValuesEdit;

class SpecsEdit
{
    private $_specs = [];

    /**
     * @param $specs
     * @param $categoryId
     */

    public function __construct($specs)
    {
        $this->_specs = $specs;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (empty($this->_specs)) return false;
        $specsPack = new SpecsPack();

        foreach ($this->_specs as $spec)
        {
            $specsPack->newItem()->setBatch($spec);
            $specId = $specsPack->spec_id;
            $specsPack->addItem();
            if (!empty($spec['specs_restricted_values'])) {
                if (empty($specId)) {
                    $result = $specsPack->flush();
                    $result->last();
                    $specId = $result->spec_id;
                }
                $this->saveRestrictedValues($spec['specs_restricted_values'], $specId);
            }
        }
        $specsPack->flush();
        return true;
    }

    private function saveRestrictedValues($restrictedValues, $specId)
    {
        $specsRestrictedValues = new SpecsRestrictedValuesEdit($restrictedValues, $specId);
        $specsRestrictedValues->save();
    }
}