<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Specs;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\ObjFactory;
use yii\db\Query;

/**
 * @method $this loadRestrictedValues (bool $set = true)
 * @method SpecsPack getItemsByIds($ids)
 * @var SpecsPack _items
 *
 * Class Dolls
 * @package app\models\ProductCollection
 */
class Specs extends ModelAccessor
{
    protected $_options = [
        '_loadRestrictedValues' => false,
    ];

    protected function initialize()
    {
        $this->_items = new SpecsPack();
    }

    /**
     * Load specification
     *
     * @return bool
     */
    protected function _loadRestrictedValues()
    {
        if (!empty($this->_ids))
        {
            $query = new Query();

            $restrictedValues = $query->select('spec_id, specs_restricted_values_id, value')
                ->from('specs_restricted_values')
                ->where(['IN', 'spec_id', $this->_ids['spec_id']])
                ->createCommand(ObjFactory::dbConnection())->queryAll();

            $this->_mergeData($restrictedValues, 'specs_restricted_values');
            return true;
        } else {
            return false;
        }
    }
}