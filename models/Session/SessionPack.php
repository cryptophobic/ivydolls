<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:42 PM
 */

namespace app\models\Session;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Products\ProductsPack;
use app\models\Users\UsersPack;


/**
 * Class UsersPack
 *
 * @property string user_id
 * @property string session_id
 * @property string session
 * @property int expires
 * @property string updated
 * @property UsersPack users
 * @method SessionPack flush
 */
class SessionPack extends ItemsPack
{

    protected static $_primaryKey = ['session_id'];

    protected static $_tableName = 'session';

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    protected function isProperType($name, $value)
    {
        if($name === 'users' && $value instanceof UsersPack){
            return parent::isProperType($name, $value);
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['users'] = new UsersPack();
        return parent::initializeReferences();
    }

    protected $_fields = [
        'user_id' => null,
        'session_id' => null,
        'session' => null,
        'expires' => null,
        'updated' => null,
    ];

    protected $_references = [
        'users' => null
    ];

    protected $_mandatory = [
        'user_id' => null,
        'session_id' => null,
        'session' => null,
        'expires' => null,
    ];
}