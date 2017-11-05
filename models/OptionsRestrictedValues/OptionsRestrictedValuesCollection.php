<?php

namespace app\models\OptionsRestrictedValues;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * @method OptionsRestrictedValuesPack getItems ($offset = 0)
 * @method OptionsRestrictedValuesPack getNext ()
 * @method OptionsRestrictedValuesPack getAll ()
 *
 * Class OptionsRestrictedValuesCollection
 */
class OptionsRestrictedValuesCollection extends Collection
{
    /**
     * @var array
     */
    private $_optionIds = [];

    /**
     * @var string
     */
    private $_name = '';

    /**
     * @param array|int $optionIds
     */
    public function setOptionIds($optionIds)
    {
        if (!is_array($optionIds))
        {
            $optionIds = [$optionIds];
        }
        $this->_optionIds = $optionIds;
    }

    /**
     * @param string $name
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
            $query->andWhere('value like :keyword', [':keyword' => $this->_keyword.'%']);
        }
        if ($this->_name)
        {
            $query->andWhere('value = :name', [':name' => $this->_name]);
        }
        if ($this->_optionIds)
        {
            $query->andWhere(['IN', 'option_id', $this->_optionIds]);
        }
        return $query;
    }

    /**
     * @return OptionsRestrictedValues
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new OptionsRestrictedValues();
        }

        return $this->_model;
    }
}