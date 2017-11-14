<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 11/12/2017
 * Time: 12:55 PM
 */

namespace app\models\Products;


use app\models\Category\Categories;
use app\models\ObjFactory;

class ProductsSearch
{
    public $_categoryId = 112;

    public $_height = 0;

    public $_offset = 0;

    public $_keyword = '';

    public $_productIds = [];

    public $_brandIds = [];

    private $_newOffset = 0;

    private $_scrollUrl = '';

    private $_count = -1;

    private $_productCollection = '';

    private $_filters = [];

    /**
     * @var ProductsPack
     */
    private $_products = null;

    public function __construct()
    {
        $this->_productCollection = new ProductsCollection();
    }

    private function _loadProducts()
    {
        if (!empty($this->_productIds)) {
            $products = new Products();
            $this->_products = $products->loadImages()->getItemsByIds(['product_id' => $this->_productIds]);
        } else {

            if (!empty($this->_categoryId)) {
                $this->_productCollection->setCategoryId($this->_categoryId);
            }

            if (!empty($this->_height)) {
                $this->_productCollection->setHeight($this->_height);
            }

            if (!empty($this->_keyword)) {
                $this->_productCollection->setKeyWord($this->_keyword);
            }

            $this->_products = $this->_productCollection->getItems($this->_offset);
            if($this->_products->current()) {
                $this->_newOffset = $this->_productCollection->getOffset();
            }
        }
    }

    public function getProducts()
    {
        if (empty($this->_products))
        {
            $this->_loadProducts();
        }
        return $this->_products;
    }

    public function getCount()
    {
        if ($this->_count < 0 && $this->getProducts())
        {
            if (!empty($this->_productIds))
            {
                $this->_count = $this->_products->count();
            } else {
                $this->_count = $this->_productCollection->getCount();
            }
        }
        return $this->_count;
    }


    /**
     * @return array
     */
    public function getFilters()
    {
        if (empty($this->_filters) && $this->getProducts()) {
            if (!empty($this->_productIds)) {
                $this->_filters['Продукты'] = [];
                for ($this->_products->first(); $this->_products->current(); $this->_products->next()) {
                    $this->_filters['Продукты'][] = $this->_products->name;
                }
                $this->_products->first();
                if (!empty($this->_filters['Продукты'])) {
                    $this->_filters['Продукты'] = implode(" и ", $this->_filters['Продукты']);
                }
            } else {

                if (!empty($this->_categoryId)) {
                    $category = new Categories();
                    $result = $category->getItemsByIds(['category_id' => $this->_categoryId]);
                    if ($result->current()) {
                        $this->_filters['Категория'] = $result->name;
                    }
                }

                if (!empty($this->_height)) {
                    $heights = ProductsCollection::heights();
                    if (!empty($heights[$this->_height])) {
                        $this->_filters['Рост'] = $heights[$this->_height];
                    }
                }

                if (!empty($this->_keyword)) {
                    $this->_filters['Ключевое слово'] = $this->_keyword;
                }
            }
        }
        return $this->_filters;
    }


    public function getScrollUrl()
    {
        if (empty($this->_scrollUrl) && $this->getProducts() && $this->_newOffset)
        {
            $this->_scrollUrl = ObjFactory::urlManager()->createUrl([
                'start/load-products',
                'categoryId' => $this->_categoryId,
                'keyword' => $this->_keyword,
                'height' => $this->_height,
                'offset' => $this->_newOffset]);
        }
        return $this->_scrollUrl;
    }


}