<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Specs;

use app\models\Abstractive\Simple\ItemsDelete;
use app\models\SpecsRestrictedValues\SpecsRestrictedValuesDelete;
use app\models\SpecsRestrictedValues\SpecsRestrictedValuesPack;

class SpecsDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = SpecsPack::getTableName();
        $this->_idName = SpecsPack::getPrimaryKey();
        parent::__construct($ids);
    }

    private function deleteRestrictedValues()
    {
        $specs = new Specs();
        $specs->loadRestrictedValues();

        $ids = [];
        foreach ($this->_ids['spec_id'] as $specId)
        {
            $result = $specs->getItemsByIds($specId);

            if ($result->moveToItem($specId) && $result->specs_restricted_values != null)
            {
                $restrictedIds = $result->specs_restricted_values->getKeysArray();
                foreach (SpecsRestrictedValuesPack::getPrimaryKey() as $key)
                {
                    if(!empty($restrictedIds[$key])) {
                        if (empty($ids[$key])) {
                            $ids[$key] = [];
                        }

                        $ids[$key] = array_merge($ids[$key], $restrictedIds[$key]);
                    }
                }
            }
            if (!empty($ids)) {
                $specsRestrictedValuesDelete = new SpecsRestrictedValuesDelete($ids);
                $specsRestrictedValuesDelete->delete();
            }
        }
        /*if (count($ids) > 0) {
            $optionsRestrictedValuesDelete = new SpecsRestrictedValuesDelete($ids);
            $optionsRestrictedValuesDelete->delete();
        }*/
    }

    public function delete()
    {
        $this->deleteRestrictedValues();
        parent::delete();
    }

}