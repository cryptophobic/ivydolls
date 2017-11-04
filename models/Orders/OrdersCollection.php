<?php

namespace app\models\Orders;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class UsersCollection
 * @method OrdersPack getItems ($offset = 0)
 */
class OrdersCollection extends Collection
{
    private $_userIds = [];

    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Orders();
        }

        return $this->_model;
    }

    public function setUserIds($userIds)
    {
        $this->_userIds = $userIds;
        return $this;
    }

    /**
     * @param Query $query
     * @return mixed
     */
    protected function _applyFilters($query)
    {
        if ($this->_userIds)
        {
            $query->andWhere(['IN', 'user_id', $this->_userIds]);
        }


        if ($this->_keyword)
        {
            $query->andWhere('name like :keyword', [':keyword' => $this->_keyword.'%']);
        }

        return $query;
    }
}