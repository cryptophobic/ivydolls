<?php

namespace app\models\ProductsOptions;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class ProductsOptionsCollection
 * @package app\models\ProductsOptions
 * @method ProductsOptionsPack getItems ($offset = 0)
 */
class ProductsOptionsCollection extends Collection
{
    /**
     * @var array
     */
    private $_productIds = [];

    /**
     * @param array|int $productIds
     */
    public function setProductIds($productIds)
    {
        if (!is_array($productIds))
        {
            $productIds = [$productIds];
        }
        $this->_productIds = $productIds;
    }

    /**
     * @param Query $query
     * @return Query
     */
    protected function _applyFilters($query)
    {
        if ($this->_productIds)
        {
            $query->andWhere(['IN', 'product_id', $this->_productIds]);
        }
        return $query;
    }

    /**
     * @return ProductsOptions
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new ProductsOptions();
        }
        return $this->_model;
    }
}