<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\ProductsRelated;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Products\ProductsPack;


/**
 * Class SpecsRestrictedValuesPack
 *
 * @property integer product_id
 * @property integer product_related_id
 * @property ProductsPack products
 * @property float price
 * @method ProductsRelatedPack flush
 */
class ProductsRelatedPack extends ItemsPack
{
    /**
     * @param $name
     * @param $value
     * @return bool
     */
    protected function isProperType($name, $value)
    {
        if($name === 'products' && $value instanceof ProductsPack)
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
        $this->_references['products'] = new ProductsPack();
        return parent::initializeReferences();
    }


    protected $_fields = [
        'product_id' => null,
        'product_related_id' => null,
        'price' => null,
    ];

    protected static $_primaryKey = ['product_id', 'product_related_id'];

    protected static $_tableName = 'products_related';


    protected $_references = [
        'products' => null,
    ];

    protected $_mandatory = [
        'product_id' => true,
        'product_related_id' => true,
    ];

}
