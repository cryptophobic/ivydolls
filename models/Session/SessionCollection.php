<?php

namespace app\models\Session;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class SessionCollection
 * @method SessionPack getItems ($offset = 0)
 * @method SessionPack getAll
 */
class SessionCollection extends Collection
{
    private $_userIds = [];

    private $_sessions = [];

    private $_expired = false;

    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Session();
        }

        return $this->_model;
    }

    public function setUserIds($userIds)
    {
        $this->_userIds = (array)$userIds;
        return $this;
    }

    public function setSessions($sessions)
    {
        $this->_sessions = (array)$sessions;
        return $this;
    }

    /**
     * @param bool $include
     * @return $this
     */
    public function includeExpired($include = true)
    {
        $this->_expired = (bool) $include;
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

        if ($this->_sessions)
        {
            $query->andWhere(['IN', 'session', $this->_userIds]);
        }

        if (!$this->_expired)
        {
            $query->andWhere('expires >= unix_timestamp()');
        }

        return $query;
    }
}