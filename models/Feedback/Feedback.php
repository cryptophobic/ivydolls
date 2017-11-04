<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Feedback;
use app\models\Abstractive\Complex\ModelAccessor;

/**
 * @method FeedbackPack getItemsByIds ($ids)
 * @var FeedbackPack _items
 */
class Feedback extends ModelAccessor
{
    protected function initialize()
    {
        $this->_items = new FeedbackPack();
    }
}