<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Users;

use app\models\Abstractive\Simple\ItemsDelete;
use Yii;

class UsersDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = UsersPack::getTableName();
        $this->_idName = UsersPack::getPrimaryKey();
        parent::__construct($ids);
    }

    private function deleteOrders()
    {
        $ordersCollection = new OrdersCollection();
        $ordersCollection->setUserIds($this->_ids['user_id']);
        $ordersCollection->setLimit(100);
        while ($ids = $ordersCollection->getIds()) {
            $ordersDelete = new OrdersDelete($ids);
            $ordersDelete->delete();
        }
    }

    private function deleteFavourites()
    {
        $favouritesCollection = new FavouritesCollection();
        $favouritesCollection->setUserIds($this->_ids['user_id']);
        $favouritesCollection->setLimit(100);
        while ($ids = $favouritesCollection->getIds()) {
            $favouritesGroups = new FavouritesDelete($ids);
            $favouritesGroups->delete();
        }
    }

    public function delete()
    {
        $this->deleteOrders();
        $this->deleteFavourites();

        $offset = 0;
        $step = 100;
        while ($array = array_slice((array)$this->_ids['user_id'], $offset, $offset+$step))
        {
            $users = new Users();
            $items = $users->getItemsByIds($array);
            for($items->first();$items->current();$items->next())
            {
                $avatar = yii::getAlias('@app').'/web'.$items->avatar;
                if (file_exists($avatar))
                {
                    unlink($avatar);
                }
            }

            $offset += $step;
        }
        parent::delete();
    }

}