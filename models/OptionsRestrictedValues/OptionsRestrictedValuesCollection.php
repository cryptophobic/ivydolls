<?php

namespace app\models\OptionsRestrictedValues;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * @method OptionsRestrictedValuesPack getItems ($offset = 0)
 * @method OptionsRestrictedValuesPack getNext ()
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
     * @param Query $query
     * @return Query
     */
    protected function _applyFilters($query)
    {
        if ($this->_keyword)
        {
            $query->andWhere('value like :keyword', [':keyword' => $this->_keyword.'%']);
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