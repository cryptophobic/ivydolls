<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Session;
use app\components\WebUser;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Products\Products;
use app\models\Products\ProductsPack;
use app\models\Users\Users;
use app\models\Users\UsersPack;

/**
 * @method $this loadUsers (bool $set = true)
 * @method SessionPack getItemsByIds  ($ids)
 * @var SessionPack _items
 *
 */
class Session extends ModelAccessor
{
    protected $_options = [
        '_loadUsers' => false
    ];

    protected function initialize()
    {
        $this->_items = new SessionPack();
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
            $users = new Users();
            $ids = [];
            for($this->_items->first();$this->_items->current();$this->_items->next())
            {
                $ids[] = $this->_items->user_id;
            }
            if (!empty($ids))
            {
                $items = $users->getItemsByIds(['user_id' => $ids]);
                $this->_items->mergeDataPack($items, UsersPack::getTableName());
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $userId
     * @return SessionPack
     */
    public static function newSession($userId)
    {
        self::dropUserSession($userId);
        $candidate = uniqid($userId, true);
        while (self::getUser($candidate)->current())
        {
            $candidate = uniqid($userId, true);
        }

        $sessionPack = new SessionPack();
        $sessionPack->newItem();
        $time = time();
        $time += (60*60*24*7);
        $sessionPack->expires = time() + WebUser::EXPIRES;//date("Y-m-d H:i:s", $time);
        $sessionPack->session = $candidate;
        $sessionPack->user_id = $userId;
        return $sessionPack->flush();
    }

    /**
     * @param $session
     * @return SessionPack
     */
    public static function getUser($session)
    {
        $sessionCollection = new SessionCollection();
        $sessionCollection->getModel()->loadUsers();
        $sessionCollection->setSessions($session);
        return $sessionCollection->getAll();
    }


    /**
     * @param $userId
     * @return SessionPack
     */
    public static function getSession($userId)
    {
        $sessionCollection = new SessionCollection();
        $sessionCollection->setUserIds($userId);
        return $sessionCollection->getAll();
    }

    /**
     * @param $userId
     * @return int
     */
    public static function dropUserSession($userId)
    {
        $deleted = 0;
        $sessionCollection = new SessionCollection();
        $sessionCollection->setUserIds($userId);
        $sessionIds = $sessionCollection->getIds();
        if (count($sessionIds['session_id']) > 0)
        {
            $sessionsDelete = new SessionDelete($sessionIds);
            $deleted = $sessionsDelete->delete();
        }
        return $deleted;
    }
}