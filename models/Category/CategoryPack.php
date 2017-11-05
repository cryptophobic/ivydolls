<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:42 PM
 */

namespace app\models\Category;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Options\OptionsPack;
use app\models\Specs\SpecsPack;


/**
 * Class CategoryPack
 *
 * @property string name
 * @property string description
 * @property string image
 * @property string image_thumb
 * @property integer parent_category_id
 * @property integer category_id
 * @property integer active
 * @property OptionsPack options
 * @property SpecsPack specs
 * @method CategoryPack flush
 */
class CategoryPack extends ItemsPack
{

    protected static $_primaryKey = ['category_id'];

    protected static $_tableName = 'categories';

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    protected function isProperType($name, $value)
    {
        if(($name === 'options' && $value instanceof OptionsPack)
            || ($name === 'specs' && $value instanceof SpecsPack)
            || ($name === 'categories' && $value instanceof CategoryPack)){
            return parent::isProperType($name, $value);
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['options'] = new OptionsPack();
        $this->_references['specs'] = new SpecsPack();
        $this->_references['categories'] = new CategoryPack();
        return parent::initializeReferences();
    }

    protected $_fields = [
        'category_id' => null,
        'name' => null,
        'description' => null,
        'image' => null,
        'image_thumb' => null,
        'parent_category_id' => 0,
        'active' => 1
    ];

    protected $_references = [
        'options' => null,
        'specs' => null,
        'categories' => null,
    ];

    protected $_mandatory = [
        'name' => true
    ];
}