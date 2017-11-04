<?php

namespace app\models\ProductsSpecs;

use app\models\Abstractive\Complex\ItemsDelete;

class ProductsSpecsDelete extends ItemsDelete
{
    public function __construct($ids)
    {
        $this->_tableName = ProductsSpecsPack::getTableName();
        $this->_idName = ProductsSpecsPack::getPrimaryKey();
        parent::__construct($ids);
    }

}