<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 10/16/2017
 * Time: 1:39 PM
 */

namespace app\models\Products;


use app\models\Brands\BrandsCollection;
use app\models\Brands\BrandsPack;
use app\models\Category\Categories;
use app\models\Category\CategoryPack;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsPack;
use app\models\ProductsRelated\ProductsRelatedPack;
use app\models\Specs\SpecsCollection;
use app\models\Specs\SpecsPack;

class ProductPresentation
{
    /**
     * @var int
     */
    private $_productId = 0;
    /**
     * @var int
     */
    private $_categoryId = 0;

    /**
     * @var ProductsPack
     */
    private $_product = null;

    /**
     * @var SpecsPack
     */
    private $_specs = null;

    /**
     * @var OptionsPack
     */
    private $_options = null;

    /**
     * @var BrandsPack
     */
    private $_brands = null;

    /**
     * @var array
     */
    private $_relatedCategories = [];

    /**
     * ProductPresentation constructor.
     * @param int $productId
     * @param int $categoryId
     */
    public function __construct($productId = 0, $categoryId = 0)
    {
        $this->_productId = $productId;
        $this->_categoryId = $categoryId;
    }

    /**
     * @return ProductsPack
     */
    public function getProduct()
    {
        if ($this->_product === null)
        {
            if(!empty($this->_productId)) {
                $doll = new Products();
                $doll->loadSpecs()->loadOptions()->loadBrandInfo()->loadCategoryInfo()->loadImages()->loadRelated();
                $this->_product = $doll->getItemsByIds($this->_productId);
            } else {
                $this->_product = new ProductsPack();
                $this->_product->newItem()->category_id = $this->_categoryId;
                $category = new Categories();
                $this->_product->categories = $category->getItemsByIds(['category_id' => [$this->_categoryId]]);
            }
        }
        return $this->_product;
    }

    /**
     * @return OptionsPack
     */
    public function getOptions()
    {
        if ($this->_options === null)
        {
            $options = new OptionsCollection();
            $options->setCategoryIds($this->getProduct()->category_id);
            $options->getModel()->loadRestrictedValues();
            $this->_options = $options->getAll();
        }
        return $this->_options;
    }

    /**
     * @return SpecsPack
     */
    public function getSpecs()
    {
        if ($this->_specs === null)
        {
            $specs = new SpecsCollection();
            $specs->setCategoryIds($this->getProduct()->category_id);
            $this->_specs = $specs->getAll();
        }
        return $this->_specs;
    }

    /**
     * @return BrandsPack
     */
    public function getBrands()
    {
        if($this->_brands === null)
        {
            $brandsCollection = new BrandsCollection();
            $this->_brands = $brandsCollection->getAll();
        }
        return $this->_brands;
    }

    /**
     * @return array
     */
    public function getRelatedCategories()
    {
        if($this->_relatedCategories === [])
        {
            $related = $this->getProduct()->products_related;
            $categories = [];
            for ($related->first(); $related->current(); $related->next())
            {
                $relatedArray = [];
                foreach (ProductsRelatedPack::getPrimaryKey() as $key)
                {
                    $relatedArray[$key] = $related->get($key);
                }
                if (empty($categories[$related->products->category_id]))
                {
                    $categories[$related->products->category_id] = [];
                }
                $categories[$related->products->category_id][] = $relatedArray;
            }
            if(!empty($categories)) {
                $categoriesModel = new Categories();
                $categoriesPack = $categoriesModel->getItemsByIds(['category_id' => array_keys($categories)]);
                /**
                 * TODO: run away from array vars
                 */
                for ($categoriesPack->first(); $categoriesPack->current(); $categoriesPack->next()) {
                    $categoryPack = new CategoryPack();
                    $categoryPack->newItem($categoriesPack);
                    $this->_relatedCategories[$categoriesPack->category_id] = [
                        'category' => $categoryPack,
                        'relatedKeys' => $categories[$categoriesPack->category_id]
                    ];
                }
            }
        }
        return $this->_relatedCategories;
    }
}