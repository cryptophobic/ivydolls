<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:49 AM
 */

namespace app\models\Abstractive\Complex;
use app\models\Abstractive\Simple\Item;

/**
 * Class ItemsPack
 * @package app\models\Abs
 */
abstract class ItemsPack extends \app\models\Abstractive\Simple\ItemsPack
{
    /**
     * @var SelectedAccessor
     */
    private $_selectedItem = null;

    /**
     * @param Item $item
     * @return SelectedAccessor
     */
    private function _getSelectedItem($item = null)
    {
        if(empty($this->_selectedItem))
        {
            $this->_selectedItem = new SelectedAccessor();
        }

        if (!empty($item))
        {
            $this->_selectedItem->setItem($item);
        }
        return $this->_selectedItem;
    }

    public function select()
    {
        $this->_getSelectedItem($this->_getCurrentItem());
    }

    public function selected()
    {
        return $this->_getSelectedItem();
    }

    protected function save()
    {
        return (new ItemStore($this))->save();
    }

    /**
     * returns key=>value array of current item primary key
     *
     * @return array
     */
    public function getCurrentKey()
    {
        $key = [];
        $primaryKey = static::getPrimaryKey();
        if (!is_array($primaryKey)) {
            $primaryKey = [$primaryKey];
        }

        foreach ($primaryKey as $keyPart)
        {
            $key[$keyPart] = $this->_currentItem->get($keyPart);
        }
        return $key;
    }

}