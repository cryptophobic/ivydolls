<?php

namespace app\models\Options;

use app\models\Abstractive\Complex\Collection;
use app\models\Category\Categories;
use yii\db\Query;

/**
 * @method OptionsPack getItems ($offset = 0)
 * @method OptionsPack getNext ()
 * @method OptionsPack getAll ()
 * Class OptionsCollection
 * @package app\models\Options
 */
class OptionsCollection extends Collection
{
    /**
     * @var array
     */
    private $_categoryIds = [];

    /**
     * @var string
     */
    private $_name = '';

    /**
     * @param array|int $categoryIds
     */
    public function setCategoryIds($categoryIds)
    {
        if (!is_array($categoryIds))
        {
            $categoryIds = Categories::getParentHierarchy($categoryIds);
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
     * @return Options
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Options();
        }

        return $this->_model;
    }
}