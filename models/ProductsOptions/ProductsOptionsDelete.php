<?php

namespace app\models\ProductsOptions;

use app\models\Abstractive\Complex\ItemsDelete;

class ProductsOptionsDelete extends ItemsDelete
{
    public function __construct($ids)
    {
        $this->_tableName = ProductsOptionsPack::getTableName();
        $this->_idName = ProductsOptionsPack::getPrimaryKey();
        parent::__construct($ids);
    }
}