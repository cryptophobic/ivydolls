<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\OptionsRestrictedValues;

use app\models\Abstractive\Simple\ItemsDelete;

class OptionsRestrictedValuesDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = OptionsRestrictedValuesPack::getTableName();
        $this->_idName = OptionsRestrictedValuesPack::getPrimaryKey();
        parent::__construct($ids);
    }

}