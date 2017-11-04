<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\Brands;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Products\ProductsPack;

/**
 * Class BrandsPack
 *
 * @property ProductsPack products
 * @property integer brand_id
 * @property string name
 * @property string url
 * @property string logo
 * @property string logo_thumb
 */
class BrandsPack extends ItemsPack
{
    protected function isProperType($name, $value)
    {
        if($name === 'products' && $value instanceof ProductsPack){
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
        'brand_id' => null,
        'name' => null,
        'url' => null,
        'logo' => null,
        'logo_thumb' => null
    ];

    protected $_references = [
        'products' => null
    ];


    protected static $_primaryKey = ['brand_id'];

    protected static $_tableName = 'brands';

    protected $_mandatory = [
        'name' => true,
    ];
}
