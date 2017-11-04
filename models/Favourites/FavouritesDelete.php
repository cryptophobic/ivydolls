<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Favourites;

use app\models\Abstractive\Simple\ItemsDelete;
use Yii;

class FavouritesDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = FavouritesPack::getTableName();
        $this->_idName = FavouritesPack::getPrimaryKey();
        parent::__construct($ids);
    }
}