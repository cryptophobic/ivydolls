<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Orders;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Products\Products;
use app\models\Products\ProductsPack;
use app\models\Users\Users;
use app\models\Users\UsersPack;

/**
 * @method $this loadProducts (bool $set = true)
 * @method $this loadUsers (bool $set = true)
 * @method OrdersPack getItemsByIds  ($ids)
 * @var OrdersPack _items
 *
 */
class Orders extends ModelAccessor
{
    protected $_options = [
        '_loadProducts' => false,
        '_loadUsers' => false
    ];

    protected function initialize()
    {
        $this->_items = new OrdersPack();
    }

    /**
     * Load specification
     *
     * @return bool
     */
    protected function _loadProducts()
    {
        if (!empty($this->_ids))
        {
            $products = new Products();
            $ids = [];
            for($this->_items->first();$this->_items->current();$this->_items->next())
            {
                $ids[] = $this->_items->product_id;
            }
            if (!empty($ids))
            {
                $items = $products->getItemsByIds(['product_id' => $ids]);
                $this->_mergeDataPack($items, ProductsPack::getTableName());
            }
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
    protected function _loadUsers()
    {
        if (!empty($this->_ids))
        {
            $products = new Users();
            $ids = [];
            for($this->_items->first();$this->_items->current();$this->_items->next())
            {
                $ids[] = $this->_items->user_id;
            }
            if (!empty($ids))
            {
                $items = $products->getItemsByIds(['user_id' => $ids]);
                $this->_mergeDataPack($items, UsersPack::getTableName());
            }
            return true;
        } else {
            return false;
        }
    }
}