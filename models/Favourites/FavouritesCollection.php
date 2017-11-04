<?php

namespace app\models\Favourites;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class FavouritesCollection
 * @method FavouritesPack getItems ($offset = 0)
 */
class FavouritesCollection extends Collection
{
    private $_userIds = [];

    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Favourites();
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
        return $query;
    }
}