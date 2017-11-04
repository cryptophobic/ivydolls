<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\Feedback;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Products\ProductsPack;

/**
 * Class FeedbackPack
 *
 * @property int feedback_id
 * @property string name
 * @property string phone
 * @property string email
 * @property string ip
 * @property string referrer
 * @property string message
 * @property string new
 * @property string updated
 */
class FeedbackPack extends ItemsPack
{
    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['products'] = new ProductsPack();
        return parent::initializeReferences();
    }


    protected $_fields = [
        'feedback_id' => null,
        'name' => null,
        'phone' => null,
        'email' => null,
        'ip' => null,
        'referrer' => null,
        'message' => null,
        'new' => 1,
        'updated' => null,
    ];

    protected static $_primaryKey = ['feedback_id'];

    protected static $_tableName = 'feedback';

    protected $_mandatory = [
        'name' => true,
        'email' => true,
        'message' => true
    ];
}
