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
        $this->_parentCategoryIds = (array)$parentCategoryIds;
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

        if ($this->_keyword)
        {
            $query->andWhere('name like :keyword', [':keyword' => $this->_keyword.'%']);
        }

        $query->andWhere('active = 1');
        return $query;
    }
}