<?php

namespace app\models\Users;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class UsersCollection
 * @method UsersPack getItems ($offset = 0)
 * @method UsersPack getAll
 */
class UsersCollection extends Collection
{
    private $_phones = [];

    private $_emails = [];

    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Users();
        }

        return $this->_model;
    }

    /**
     * @param array|string $phones
     * @return $this
     */
    public function setPhones($phones)
    {
        $this->_phones = (array)$phones;
        return $this;
    }

    /**
     * @param array|string $emails
     * @return $this
     */
    public function setEmails($emails)
    {
        $this->_emails = $emails;
        return $this;
    }

    /**
     * @param Query $query
     * @return mixed
     */
    protected function _applyFilters($query)
    {
        if (!empty($this->_emails))
        {
            $query->andWhere(['IN', 'email', $this->_emails]);
        }

        if (!empty($this->_phones))
        {
            $query->andWhere(['IN', 'phone', $this->_phones]);
        }

        if ($this->_keyword)
        {
            $query->andWhere('name like :keyword', [':keyword' => $this->_keyword.'%']);
        }

        return $query;
    }
}