<?php

namespace app\models\Category;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class CategoryCollection
 * @package app\models\Category
 * @method CategoryPack getItems ($offset = 0)
 */
class CategoryCollection extends Collection
{

    private $_parentCategoryIds = [0];

    private $_minNo = 0;

    private $_maxNo = 0;

    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Categories();
        }

        return $this->_model;
    }

    /**
     * @param int|array $parentCategoryIds
     * @return $this
     */
    public function setParentCategoryIds($parentCategoryIds)
    {
        if ($parentCategoryIds < 0)
        {
            $this->_parentCategoryIds = null;
        } else {
            $this->_parentCategoryIds = (array)$parentCategoryIds;
        }
        return $this;
    }

    /**
     * @param $min
     * @param $max
     * @return $this
     */
    public function setNoRange($min, $max)
    {
        if ($min > $max)
        {
            $swap = $min;
            $min = $max;
            $max = $swap;
        }
        $this->_minNo = $min;
        $this->_maxNo = $max;
        return $this;
    }

    /**
     * @param Query $query
     * @return mixed
     */
    protected function _applyFilters($query)
    {
        if (!empty($this->_parentCategoryIds))
        {
            $query->andWhere(['IN', 'parent_category_id', $this->_parentCategoryIds]);
        }

        if (!empty($this->_maxNo) && !empty($this->_minNo))
        {
            $query->andWhere('no >= :minNo', [':minNo' => $this->_minNo]);
            $query->andWhere('no <= :maxNo', [':maxNo' => $this->_maxNo]);
        }

        if ($this->_keyword)
        {
            $query->andWhere('name like :keyword', [':keyword' => $this->_keyword.'%']);
        }

        $query->andWhere('active = 1');
        return $query;
    }
}