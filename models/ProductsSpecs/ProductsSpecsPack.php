<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\ProductsSpecs;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Products\ProductsPack;
use app\models\Specs\SpecsPack;


/**
 * Class ProductsSpecs
 *
 * @property integer spec_id
 * @property integer product_id
 * @property string value
 * @property ProductsPack products
 * @property SpecsPack specs
 * @method ProductsSpecsPack flush
 */
class ProductsSpecsPack extends ItemsPack
{
    protected $_fields = [
        'spec_id' => null,
        'product_id' => null,
        'value' => null,
    ];

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    protected function isProperType($name, $value)
    {
        if(($name === 'products' && $value instanceof ProductsPack)
            || ($name === 'specs' && $value instanceof SpecsPack)){
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
        $this->_references['specs'] = new SpecsPack();
        return parent::initializeReferences();
    }

    protected static $_primaryKey = ['spec_id', 'product_id'];

    protected static $_tableName = 'products_specs';

    protected $_references = [
        'products' => null,
        'specs' => null
    ];


    protected $_mandatory = [
        'product_id' => true,
        'spec_id' => true,
        'value' => true
    ];
}