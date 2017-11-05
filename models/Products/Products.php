<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Products;

use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Category\Categories;
use app\models\Category\CategoryPack;
use app\models\ObjFactory;
use app\models\ProductsImages\ProductsImagesCollection;
use app\models\ProductsImages\ProductsImagesPack;
use app\models\ProductsOptions\ProductsOptionsCollection;
use app\models\ProductsOptions\ProductsOptionsPack;
use app\models\ProductsRelated\ProductsRelated;
use app\models\ProductsRelated\ProductsRelatedCollection;
use app\models\ProductsRelated\ProductsRelatedPack;
use app\models\ProductsSpecs\ProductsSpecsCollection;
use app\models\ProductsSpecs\ProductsSpecsPack;
use app\models\Brands\BrandsPack;
use app\models\Brands\Brands;
use yii\db\Query;

/**
 * @method $this loadImages (bool $set = true)
 * @method $this loadSpecs (bool $set = true)
 * @method $this loadOptions (bool $set = true)
 * @method $this loadBrandInfo (bool $set = true)
 * @method $this loadCategoryInfo (bool $set = true)
 * @method $this loadRelated (bool $set = true)
 * @method ProductsPack getItemsByIds  ($ids)
 * @var ProductsPack _items
 */
class Products extends ModelAccessor
{
    protected $_options = [
        '_loadImages' => false,
        '_loadSpecs' => false,
        '_loadOptions' => false,
        '_loadBrandInfo' => false,
        '_loadCategoryInfo' => false,
        '_loadRelated' => false
    ];

    /**
     * @var ProductsPack
     */
    protected $_items = null;


    private $_productMap = [];

    protected function initialize()
    {
        $this->_items = new ProductsPack();
    }

    /**
     * Load products images
     *
     * @return bool
     */
    protected function _loadImages()
    {
        if (!empty($this->_ids))
        {
            $productsImagesCollection = new ProductsImagesCollection();
            $productsImagesCollection->setProductIds($this->_ids['product_id']);
            $this->_items->mergeDataPack($productsImagesCollection->getAll(), ProductsImagesPack::getTableName());
            return true;
        } else {
            return false;
        }
    }

    /**
     * load products specifications
     *
     * @return bool
     */
    protected function _loadSpecs()
    {
        if (!empty($this->_ids))
        {
            $specsCollection = new ProductsSpecsCollection();
            $specsCollection->getModel()->loadSpecInfo();
            $specsCollection->setProductIds($this->_ids['product_id']);
            $this->_items->mergeDataPack($specsCollection->getAll(), ProductsSpecsPack::getTableName());
            return true;
        } else {
            return false;
        }
    }

    /**
     * load products options
     *
     * @return bool
     */
    protected function _loadOptions()
    {
        if (!empty($this->_ids))
        {
            $optionsCollection = new ProductsOptionsCollection();
            $optionsCollection->setProductIds($this->_ids['product_id']);
            $this->_items->mergeDataPack($optionsCollection->getAll(), ProductsOptionsPack::getTableName());

            return true;
        } else {
            return false;
        }
    }

    protected function _loadBrandInfo()
    {
        if (!empty($this->_ids))
        {
            $brands = new Brands();
            $ids = [];
            for($this->_items->first();$this->_items->current();$this->_items->next())
            {
                $ids[] = $this->_items->brand_id;
            }
            if (!empty($ids))
            {
                $items = $brands->getItemsByIds(['brand_id' => $ids]);
                $this->_items->mergeDataPack($items, BrandsPack::getTableName());
            }

            return true;
        } else {
            return false;
        }
    }

    protected function _loadCategoryInfo()
    {
        if (!empty($this->_ids))
        {
            $categories = new Categories();
            $ids = [];
            for($this->_items->first();$this->_items->current();$this->_items->next())
            {
                $ids[] = $this->_items->category_id;
            }
            if (!empty($ids))
            {
                $items = $categories->getItemsByIds(['category_id' => $ids]);
                $this->_items->mergeDataPack($items, CategoryPack::getTableName());
            }

            return true;
        } else {
            return false;
        }
    }

    protected function _loadRelated()
    {
        if (!empty($this->_ids))
        {
            $relatedCollection = new ProductsRelatedCollection();
            $relatedCollection->getModel()->loadProductInfo();
            $relatedCollection->setProductIds($this->_ids['product_id']);
            $this->_items->mergeDataPack($relatedCollection->getAll(), ProductsRelatedPack::getTableName());
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $dollNames
     * @return ProductsPack
     */
    public function getDollsByNames($dollNames)
    {
        if (!is_array($dollNames))
        {
            $dollNames = [$dollNames];
        }

        $products = (new Query())
            ->select("product_id, name")
            ->from('products')
            ->where(["IN", "name", $dollNames])
            ->createCommand(ObjFactory::dbConnection())->queryAll();

        $productIds = [];

        foreach($products as $product)
        {
            $this->_productMap[$product['product_id']] = $product['name'];
            $productIds[] = $product['product_id'];
        }

        $dolls =  $this->getItemsByIds($productIds);
        return $dolls;
    }
}