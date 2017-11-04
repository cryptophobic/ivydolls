<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Session;

use app\models\Abstractive\Simple\ItemsDelete;

class SessionDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = SessionPack::getTableName();
        $this->_idName = SessionPack::getPrimaryKey();
        parent::__construct($ids);
    }
}