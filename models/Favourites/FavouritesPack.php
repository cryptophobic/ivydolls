<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:42 PM
 */

namespace app\models\Favourites;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Products\ProductsPack;
use app\models\Users\UsersPack;


/**
 * Class UsersPack
 *
 * @property string user_id
 * @property string product_id
 * @property string updated
 * @property ProductsPack products
 * @property UsersPack users
 * @method FavouritesPack flush
 */
class FavouritesPack extends ItemsPack
{

    protected static $_primaryKey = ['user_id','product_id'];

    protected static $_tableName = 'favourites';

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    protected function isProperType($name, $value)
    {
        if(($name === 'products' && $value instanceof ProductsPack)
            || ($name === 'users' && $value instanceof UsersPack)){
            return parent::isProperType($name, $value);
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['products'] = new ProductsPack();
        $this->_references['users'] = new UsersPack();
        return parent::initializeReferences();
    }

    protected $_fields = [
        'user_id' => null,
        'product_id' => null,
        'updated' => null,
    ];

    protected $_references = [
        'products' => null,
        'users' => null
    ];

    protected $_mandatory = [
        'user_id' => true,
        'product_id' => true,
    ];
}