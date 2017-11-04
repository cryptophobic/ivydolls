<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Orders;

use app\models\Abstractive\Simple\ItemsDelete;
use Yii;

class OrdersDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = OrdersPack::getTableName();
        $this->_idName = OrdersPack::getPrimaryKey();
        parent::__construct($ids);
    }
}