<?php

namespace app\models\ProductsRelated;

use app\models\Abstractive\Complex\ItemsDelete;

class ProductsRelatedDelete extends ItemsDelete
{
    public function __construct($ids)
    {
        $this->_tableName = ProductsRelatedPack::getTableName();
        $this->_idName = ProductsRelatedPack::getPrimaryKey();
        parent::__construct($ids);
    }
}