<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:16 PM
 */

namespace app\models\Feedback;

use app\models\BO\ResampleImages;
use Yii;

class FeedbackEdit
{
    private $_brandsData = [];

    public function __construct($brandsData)
    {
        $this->_brandsData = $brandsData;
    }

    public function save()
    {
        if (empty($this->_brandsData)) return false;
        $brandsPack = new FeedbackPack();

        foreach ($this->_brandsData as $brand) {
            $brandsPack->setBatch($brand);
            $brandsPack->addItem();
        }
        $brandsPack->flush();
        return true;
    }
}