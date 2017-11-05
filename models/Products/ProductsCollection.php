<?php

namespace app\models\Products;

use app\models\ObjFactory;
use yii\db\Query;
use app\models\Abstractive\Complex\Collection;

/**
 * Class ProductsCollection
 * @method ProductsPack getItems($offset = 0)
 * @method ProductsPack getAll()
 * @package app\models\Products
 */

class ProductsCollection extends Collection
{
    const HEIGHT_80CM = 80;
    const HEIGHT_GRACE = 69;
    const HEIGHT_ELEGANT = 63;
    const HEIGHT_BLOOM = 58;
    const HEIGHT_BUD = 40;
    const HEIGHT_NAVIETE = 26;
    const UNCATEGORIZED = 1;
    const PETS = 2;

    private $_heightMapping = [
        ProductsCollection::HEIGHT_80CM => [80, 110],
        ProductsCollection::HEIGHT_GRACE => [69, 75],
        ProductsCollection::HEIGHT_ELEGANT => [63, 68],
        ProductsCollection::HEIGHT_BLOOM => [58, 62],
        ProductsCollection::HEIGHT_BUD => [40, 45],
        ProductsCollection::HEIGHT_NAVIETE => [26, 27],
        ProductsCollection::UNCATEGORIZED => [0,0],
        ProductsCollection::PETS => [0,0],
    ];

    /**
     * @var int
     */
    private $_categoryId = 0;

    /**
     * @var int
     */
    private $_brandIds = 0;

    /**
     * @var string
     */
    private $_originalUrls = '';

    /**
     * @return Products
     */
    public function getModel()
    {
        if ($this->_model == null)
        {
            $this->_model = new Products();
        }

        return $this->_model;
    }

    public static function heights()
    {
        return [
            static::HEIGHT_80CM => "80 см",
            static::HEIGHT_GRACE => "Grace (69~75 см)",
            static::HEIGHT_ELEGANT => "Elegant (63~68 см)",
            static::HEIGHT_BLOOM => "Bloom (58~62 см)",
            static::HEIGHT_BUD => "Bud (42~45 см)",
            static::HEIGHT_NAVIETE => "Naivete (26~27 см)",
            static::UNCATEGORIZED => "Без размера",
            //static::PETS => "Питомцы"
        ];
    }

    const heightId = 114;

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight($height)
    {
        if (!empty($this->_heightMapping[$height])) {
            $this->_filter[ProductsCollection::heightId] = $this->_heightMapping[$height];
        }
        return $this;
    }

    /**
     * @param int $categoryId
     * @return $this
     */
    public function setCategoryId($categoryId)
    {
        $this->_categoryId = $categoryId;
        return $this;
    }

    /**
     * @param array|int $brandIds
     * @return $this
     */
    public function setBrandIds($brandIds)
    {
        $this->_brandIds = (array)$brandIds;
        return $this;
    }

    /**
     * @param $originalUrls
     * @return $this
     */
    public function setOriginalUrls($originalUrls)
    {
        $this->_originalUrls = (array)$originalUrls;
        return $this;
    }

    /**
     * @param Query $query
     * @return Query
     */
    protected function _applyFilters($query)
    {
        if ($this->_filter && $this->_filter[ProductsCollection::heightId][0] > 0) {
            $query->innerJoin('products_specs ps', 'p.product_id=ps.product_id and ps.spec_id=:heightId', [':heightId' => ProductsCollection::heightId]);
            $filter = $this->_filter[ProductsCollection::heightId];
            $query->andWhere('ps.value between :min and :max', [':min' => $filter[0], ':max' => $filter[1]]);
        } elseif ($this->_filter && $this->_filter[ProductsCollection::heightId][0] == 0) {
            $query->leftJoin('products_specs ps', 'p.product_id=ps.product_id and ps.spec_id=:heightId', [':heightId' => ProductsCollection::heightId]);
            $query->andWhere('ps.value IS NULL');
        } elseif ($this->_categoryId == 112) {
            //$query->innerJoin('products_specs ps', 'p.product_id=ps.product_id and ps.spec_id=:heightId', [':heightId' => ProductsCollection::heightId]);
        }

        if ($this->_originalUrls)
        {
            $query->andWhere(['IN', 'p.original_url', $this->_originalUrls]);
        }

        if ($this->_categoryId)
        {
            $query->andWhere('p.category_id = :categoryId', [':categoryId' => $this->_categoryId]);
        }

        if ($this->_brandIds)
        {
            $query->andWhere(['IN', 'p.brand_id', $this->_brandIds]);
        }

        if ($this->_keyword)
        {
            $query->andWhere('p.name like :keyword', [':keyword' => '%'.$this->_keyword.'%']);
        }

        $query->andWhere('p.active = 1');
        return $query;
    }


    /**
     * @return ProductsPack
     */
    public function getNext()
    {
        $query = new Query();

        $query->select("p.product_id")->from("products p");

        $query = $this->_applyFilters($query);

        $query->orderBy('p.product_id asc')
            ->offset($this->_offset)
            ->limit($this->_limit);

        $result = $query->createCommand(ObjFactory::dbConnection())->queryColumn();
        $this->_offset += $this->_limit;
        $dolls = $this->getModel();

        $items = $dolls->loadImages()->loadSpecs()->getItemsByIds(['product_id' => $result]);

        return $items;
    }
}