<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:26 PM
 */

namespace app\models\OptionsGroups;
use app\models\Abstractive\Complex\ItemsPack;
use app\models\Options\OptionsPack;

/**
 * Class OptionsGroupPack
 *
 * @deprecated
 *
 * @property integer group_id
 * @property string name
 * @property string description
 * @property integer category_id
 * @property OptionsPack options
 * @method OptionsGroupsPack flush
 */
class OptionsGroupsPack extends ItemsPack
{
    protected static $_primaryKey = ['group_id'];

    protected static $_tableName = 'options_groups';

    protected function isProperType($name, $value)
    {
        if($name === 'options' && $value instanceof OptionsPack){
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        $this->_references['options'] = new OptionsPack();
        return parent::initializeReferences();
    }



    protected $_fields = [
        'group_id' => null,
        'name' => null,
        'description' => null,
        'category_id' => null
    ];

    protected $_references = [
        'options' => null
    ];

    protected $_mandatory = [
        'name' => true,
        'category_id' => true
    ];
}