<?php

namespace app\models\Feedback;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * @method FeedbackPack getItems ($offset = 0)
 *
 * Class FeedbackCollection
 * @package app\models\Feedback
 */
class FeedbackCollection extends Collection
{
    private $_new = null;

    /**
     * @return Feedback
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Feedback();
        }
        return $this->_model;
    }

    /**
     * @param int $new
     */
    public function setNew($new)
    {
        $this->_new = (int)$new;
    }

    /**
     * @param Query $query
     * @return mixed
     */
    protected function _applyFilters($query)
    {
        if ($this->_new !== null)
        {
            $query->andWhere(['new' => $this->_new]);
        }

        if ($this->_keyword)
        {
            $query->andWhere('name like :keyword', [':keyword' => $this->_keyword.'%']);
        }
        return $query;
    }

}