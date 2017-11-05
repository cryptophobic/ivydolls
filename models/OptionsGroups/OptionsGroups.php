<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\OptionsGroups;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\ObjFactory;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsPack;
use yii\db\Query;

/**
 * @method $this loadOptions (bool $set = true)
 * @var OptionsGroupsPack _items
 * @deprecated
 *
 */
class OptionsGroups extends ModelAccessor
{
    protected $_options = [
        '_loadOptions' => false
    ];

    protected function initialize()
    {
        $this->_items = new OptionsGroupsPack();
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
            $optionsCollection = new OptionsCollection();
            $optionsCollection->getModel()->loadRestrictedValues();
            $optionsCollection->setGroupIds($this->_ids['group_id']);
            $options = new OptionsPack();

            for ($optionsPack = $optionsCollection->getItems();
                $optionsPack->current();
                $optionsPack = $optionsCollection->getNext())
            {
                $options->merge($optionsPack);
            }
            $this->_items->mergeDataPack($options, 'options');
            return true;
        } else {
            return false;
        }
    }
}