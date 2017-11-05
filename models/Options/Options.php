<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Options;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\ObjFactory;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesCollection;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesPack;
use yii\db\Query;

/**
 * @method $this loadRestrictedValues (bool $set = true)
 * @method OptionsPack getItemsByIds ($ids)
 * @var OptionsPack _items
 */
class Options extends ModelAccessor
{
    protected $_options = [
        '_loadRestrictedValues' => false
    ];

    protected function initialize()
    {
        $this->_items = new OptionsPack();
    }

    /**
     * load options restricted values
     *
     * @return bool
     */
    protected function _loadRestrictedValues()
    {
        if (!empty($this->_ids))
        {
            $optionsRestrictedValuesCollection = new OptionsRestrictedValuesCollection();
            $optionsRestrictedValuesCollection->setOptionIds($this->_ids['option_id']);
            $this->_items->mergeDataPack($optionsRestrictedValuesCollection->getAll(), 'options_restricted_values');
            return true;
        } else {
            return false;
        }
    }
}