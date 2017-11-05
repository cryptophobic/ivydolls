<?php

namespace app\models\Specs;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class SpecsCollection
 * @package app\models\Specs
 * @method SpecsPack getAll
 */
class SpecsCollection extends Collection
{
    /**
     * @var array
     */
    private $_categoryIds = [];

    private $_name = '';

    /**
     * @param array|int $categoryIds
     */
    public function setCategoryIds($categoryIds)
    {
        if (!is_array($categoryIds))
        {
            $categoryIds = [$categoryIds];
        }
        $this->_categoryIds = $categoryIds;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @param Query $query
     * @return Query
     */
    protected function _applyFilters($query)
    {
        if ($this->_keyword)
        {
            $query->andWhere('name like :keyword', [':keyword' => $this->_keyword.'%']);
        }
        if ($this->_name)
        {
            $query->andWhere('name = :name', [':name' => $this->_name]);
        }
        if ($this->_categoryIds)
        {
            $query->andWhere(['IN', 'category_id', $this->_categoryIds]);
        }
        return $query;
    }

    /**
     * @return Specs
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Specs();
        }

        return $this->_model;
    }
}