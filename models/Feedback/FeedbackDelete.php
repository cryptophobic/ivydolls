<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Feedback;

use app\models\Abstractive\Complex\ItemsDelete;
use Yii;

class FeedbackDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = FeedbackPack::getTableName();
        $this->_idName = FeedbackPack::getPrimaryKey();
        parent::__construct($ids);
    }
}