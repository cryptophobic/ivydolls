<?php

namespace app\models\ProductsImages;

use app\models\Abstractive\Complex\Collection;
use yii\db\Query;

/**
 * Class ProductsImagesCollection
 * @package app\models\ProductsImages
 * @method ProductsImagesPack getItems ($offset = 0)
 */
class ProductsImagesCollection extends Collection
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
     * @return ProductsImages
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new ProductsImages();
        }
        return $this->_model;
    }
}