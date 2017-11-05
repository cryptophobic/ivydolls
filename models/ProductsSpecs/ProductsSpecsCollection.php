<?php

namespace app\models\ProductsSpecs;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class ProductsSpecsCollection
 * @package app\models\ProductsSpecs
 * @method ProductsSpecsPack getItems ($offset = 0)
 * @method ProductsSpecsPack getNext
 * @method ProductsSpecsPack getAll
 */
class ProductsSpecsCollection extends Collection
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
     * @return ProductsSpecs
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new ProductsSpecs();
        }
        return $this->_model;
    }
}