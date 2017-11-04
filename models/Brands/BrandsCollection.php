<?php

namespace app\models\Brands;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * @method BrandsPack getItems ($offset = 0)
 *
 * Class BrandsCollection
 * @package app\models\Brands
 */
class BrandsCollection extends Collection
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
     * @return mixed
     */
    protected function _applyFilters($query)
    {
        if ($this->_productIds)
        {
            $query->andWhere(['IN', 'product_id', $this->_productIds]);
        }

        if ($this->_keyword)
        {
            $query->andWhere('name like :keyword', [':keyword' => $this->_keyword.'%']);
        }
        return $query;
    }

    /**
     * @return Brands
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Brands();
        }

        return $this->_model;
    }
}