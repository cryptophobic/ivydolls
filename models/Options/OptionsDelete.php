<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Options;

use app\models\Abstractive\Simple\ItemsDelete;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesDelete;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesPack;

class OptionsDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = OptionsPack::getTableName();
        $this->_idName = OptionsPack::getPrimaryKey();
        parent::__construct($ids);
    }


    private function deleteRestrictedValues()
    {
        $options = new Options();
        $options->loadRestrictedValues();

        $ids = [];
        foreach ($this->_ids['option_id'] as $optionId)
        {
            $result = $options->getItemsByIds(['option_id' => $optionId]);
            if ($result->moveToItem($optionId) && $result->options_restricted_values != null)
            {
                $restrictedIds = $result->options_restricted_values->getKeysArray();
                foreach (OptionsRestrictedValuesPack::getPrimaryKey() as $key)
                {

                    if(!empty($restrictedIds[$key])) {
                        if (empty($ids[$key])) {
                            $ids[$key] = [];
                        }

                        $ids[$key] = array_merge($ids[$key], $restrictedIds[$key]);
                    }
                }
            }
            if(!empty($ids)) {
                $optionsRestrictedValuesDelete = new OptionsRestrictedValuesDelete($ids);
                $optionsRestrictedValuesDelete->delete();
            }
        }
        /*if (count($ids) > 0) {
            $optionsRestrictedValuesDelete = new OptionsRestrictedValuesDelete($ids);
            $optionsRestrictedValuesDelete->delete();
        }*/
    }

    public function delete()
    {
        $this->deleteRestrictedValues();
        parent::delete();
    }

}