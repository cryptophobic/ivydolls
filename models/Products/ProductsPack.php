<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\Products;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Category\CategoryPack;
use app\models\ProductsImages\ProductsImagesPack;
use app\models\ProductsOptions\ProductsOptionsPack;
use app\models\ProductsRelated\ProductsRelatedPack;
use app\models\ProductsSpecs\ProductsSpecsPack;
use app\models\Brands\BrandsPack;


/**
 * Class SpecsRestrictedValuesPack
 *
 * @property ProductsImagesPack products_images
 * @property ProductsOptionsPack products_options
 * @property ProductsRelatedPack products_related
 * @property ProductsSpecsPack products_specs
 * @property integer brand_id
 * @property BrandsPack brands
 * @property CategoryPack categories
 * @property integer product_id
 * @property string part_number
 * @property string description
 * @property integer category_id
 * @property integer active
 * @property string name
 * @property float price
 * @property string original_url
 * @method ProductsPack flush
 */
class ProductsPack extends ItemsPack
{
    protected $_fields = [
        'product_id' => null,
        'part_number' => null,
        'category_id' => null,
        'brand_id' => null,
        'name' => null,
        'price' => null,
        'description' => null,
        'original_url' => null,
        'active' => 1
    ];

    protected function isProperType($name, $value)
    {
        if(($name === 'categories' && $value instanceof CategoryPack) ||
            ($name === 'brands' && $value instanceof BrandsPack) ||
            ($name === 'products_specs' && $value instanceof ProductsSpecsPack) ||
            ($name === 'products_options' && $value instanceof ProductsOptionsPack) ||
            ($name === 'products_images' && $value instanceof ProductsImagesPack) ||
            ($name === 'products_related' && $value instanceof ProductsRelatedPack))
        {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['categories'] = new CategoryPack();
        $this->_references['brands'] = new BrandsPack();
        $this->_references['products_specs'] = new ProductsSpecsPack();
        $this->_references['products_options'] = new ProductsOptionsPack();
        $this->_references['products_images'] = new ProductsImagesPack();
        $this->_references['products_related'] = new ProductsRelatedPack();
        return parent::initializeReferences();
    }

    protected static $_tableName = 'products';

    protected static $_primaryKey = ['product_id'];

    protected $_references = [
        'categories' => null,
        'brands' => null,
        'products_specs' => null,
        'products_options' => null,
        'products_images' => null,
        'products_related' => null,
    ];

    protected $_mandatory = [
        'category_id' => null,
        'brand_id' => null,
        'name' => null,
    ];
}