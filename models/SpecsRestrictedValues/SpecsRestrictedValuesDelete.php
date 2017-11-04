<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\SpecsRestrictedValues;

use app\models\Abstractive\Simple\ItemsDelete;

class SpecsRestrictedValuesDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = SpecsRestrictedValuesPack::getTableName();
        $this->_idName = SpecsRestrictedValuesPack::getPrimaryKey();
        parent::__construct($ids);
    }
}