<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\OptionsGroups;

use app\models\Abstractive\Simple\ItemsDelete;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsDelete;

/**
 * @deprecated
 * Class OptionsGroupsDelete
 * @package app\models\OptionsGroups
 */
class OptionsGroupsDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = OptionsGroupsPack::getTableName();
        $this->_idName = OptionsGroupsPack::getPrimaryKey();
        parent::__construct($ids);
    }

    private function deleteOptions()
    {
        $optionsCollection = new OptionsCollection();
        $optionsCollection->getModel()->loadRestrictedValues();
        $optionsCollection->setGroupIds($this->_ids['group_id']);
        $optionsCollection->setLimit(100);
        while ($ids = $optionsCollection->getIds()) {
            $optionsDelete = new OptionsDelete($ids);
            $optionsDelete->delete();
        }
    }

    public function delete()
    {
        $this->deleteOptions();
        parent::delete();
    }

}