<?php

namespace app\models\ProductsRelated;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class ProductsRelatedCollection
 * @package app\models\ProductsRelated
 * @method ProductsRelatedPack getItems ($offset = 0)
 */
class ProductsRelatedCollection extends Collection
{
    /**
     * @var array
     */
    private $_productIds = [];

    private $_filterZeroPrice = false;

    /**
     * @param array|int $productIds
     * @return $this
     */
    public function setProductIds($productIds)
    {
        if (!is_array($productIds))
        {
            $productIds = [$productIds];
        }
        $this->_productIds = $productIds;
        return $this;
    }

    /**
     * @param bool $filter =true
     * @return $this
     */
    public function setFilterZeroPrices($filter = true)
    {
        $this->_filterZeroPrice = (bool) $filter;
        return $this;
    }

    /**
     * @param Query $query
     * @return Query
     */
    protected function _applyFilters($query)
    {
        if ($this->_filterZeroPrice === true)
        {
            $query->andWhere('price > 0')->andWhere('price < 9999');
        }

        if ($this->_productIds)
        {
            $query->andWhere(['IN', 'product_id', $this->_productIds]);
        }
        return $query;
    }

    /**
     * @return ProductsRelated
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new ProductsRelated();
        }
        return $this->_model;
    }
}