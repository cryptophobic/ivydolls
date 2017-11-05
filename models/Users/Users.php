<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Users;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Favourites\FavouritesCollection;
use app\models\Favourites\FavouritesPack;
use app\models\Orders\OrdersCollection;
use app\models\Orders\OrdersPack;

/**
 * @method $this loadFavourites (bool $set = true)
 * @method $this loadOrders (bool $set = true)
 * @method UsersPack getItemsByIds  ($ids)
 * @var UsersPack _items
 *
 */
class Users extends ModelAccessor
{
    protected $_options = [
        '_loadFavourites' => false,
        '_loadUsers' => false
    ];

    protected function initialize()
    {
        $this->_items = new UsersPack();
    }

    /**
     * Load specification
     *
     * @return bool
     */
    protected function _loadFavourites()
    {
        if (!empty($this->_ids))
        {
            $favouritesCollection = new FavouritesCollection();
            $favouritesCollection->setUserIds($this->_ids['user_id']);
            $this->_items->mergeDataPack($favouritesCollection->getAll(), FavouritesPack::getTableName());
            return true;
        } else {
            return false;
        }
    }

    /**
     * load products options
     *
     * @return bool
     */
    protected function _loadOrders()
    {
        if (!empty($this->_ids))
        {
            $ordersCollection = new OrdersCollection();
            $ordersCollection->setUserIds($this->_ids['user_id']);
            $this->_items->mergeDataPack($ordersCollection->getAll(), OrdersPack::getTableName());
            return true;
        } else {
            return false;
        }
    }
}