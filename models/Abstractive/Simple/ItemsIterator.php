<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 3:55 PM
 */

namespace app\models\Abstractive\Simple;

use app\models\ProductsOptions\ProductsOptionsPack;

abstract class ItemsIterator
{
    private $_position = 0;

    /**
     * @var Item[]
     */
    private $_list = [];

    /**
     * @var Item[]
     */
    protected $_items = [];

    /**
     * @var array of primary keys
     */
    private $_keys = [];

    /**
     * @var array of original primary keys
     */
    private $_primaryKeys = [];

    /**
     * @var array of indices
     */
    private $_indices = [];

    /**
     * @var Item
     */
    protected $_newItem = '';

    /**
     * @var Item
     */
    public $_currentItem = null;

    /**
     * ItemsIterator constructor.
     */
    public function __construct()
    {
        $this->_position = 0;
        $this->_items = $this->_list;
    }

    /**
     * @param array $values
     * @return $this
     */
    public abstract function setBatch($values);

    /**
     * @param $key
     * @return int|bool
     */
    private function _getItemByKeyString($keyString)
    {
        if (isset($this->_keys[$keyString]))
        {
            return $this->_keys[$keyString];
        } else {
            return false;
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->_items);
    }


    public function filter($key, $value)
    {
        $keyString = $this->_getKeyByArray([$key, $value]);
        if (empty($this->_indices[$keyString]))
        {
            return false;
        }
        $this->_items = $this->_indices[$keyString];
        $this->_position = 0;
        return $this->current();
    }

    public function filterClear()
    {
        $this->_items = $this->_list;
        $this->_position = 0;
    }

    /**
     * returns array of all the stored keys in Pack
     *
     * @return array
     */
    public function getKeysArray()
    {
        return $this->_primaryKeys;
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->_items = $this->_list = $this->_currentItem = $this->_keys = $this->_primaryKeys = null;
        return $this;
    }

    /**
     * @param array|string $keyValues
     * @return string
     */
    private function _getKeyByArray($keyValues)
    {
        if (!is_array($keyValues))
        {
            $keyValues = [$keyValues];
        }

        $array = array_values($keyValues);

        if (array_search(NULL, $array, true) !== FALSE)
        {
            return "";
        }

        sort($array);
        $key = implode("|", $array);

        return $key;
    }

    /**
     * @param $key
     * @param Item $item
     * @return bool
     */
    private function _indexPosition($key, $item)
    {
        foreach ($key as $keyPart => $value)
        {
            $keyString = $this->_getKeyByArray([$keyPart, $value]);
            $this->_indices[$keyString][] = $item;
        }
        return true;
    }

    /**
     * @param string|int|array $key
     * @param Item $item
     * @return bool
     */
    protected function _addItem($key, $item)
    {
        $this->filterClear();
        $keyString = $this->_getKeyByArray($key);

        if(!empty($keyString) && !empty($this->_keys[$keyString])) {
            $this->_position = $this->_keys[$keyString];
            $this->current($this->_position);
            $this->setBatch($item->getFields());
        } else {

            $this->_list[] = $item;
            $this->_position = count($this->_list) - 1;
            $this->_indexPosition($key, $this->_list[$this->_position]);

            if (is_array($key))
            {
                foreach ($key as $keyParam => $value)
                {
                    $this->_primaryKeys[$keyParam][] = $value;
                }
            }

        }

        if (!empty($keyString))
        {
            $this->_keys[$keyString] = $this->_position;
        }

        if ($this->_currentItem === $this->_newItem)
        {
            $this->_currentItem = null;
        }

        $this->_items = $this->_list;

        return $this->_position;
    }

    /**
     * @return bool
     */
    public function last()
    {
        $this->_position = 0;
        if (!empty($this->_items))
        {
            $this->_position = count($this->_items) - 1;
        }
        return $this->current();
    }

    /**
     * @return array
     */
    public function data()
    {
        if ($this->_currentItem)
        {
            return array_merge($this->_currentItem->getFields(), $this->_currentItem->getReferences());
        }
        return [];
    }

    /**
     * @return array
     */
    public function fields()
    {
        if ($this->_currentItem)
        {
            return $this->_currentItem->getFields();
        }
        return [];
    }

    /**
     * @return bool
     */
    public function first() {
        $this->_position = 0;
        return $this->current();
    }

    /**
     * @param int $pos
     * @return bool
     */
    public function current($pos = -1)
    {
        if ($pos >= 0 && $pos < count($this->_items))
        {
            $this->_position = $pos;
        }

        if (!empty($this->_items[$this->_position])){
            $this->_currentItem = $this->_items[$this->_position];
        } else {
            $this->_currentItem = null;
        }
        return !empty($this->_currentItem);
    }

    /**
     * @param $key
     * @return bool
     */
    public function moveToItem($key)
    {
        $this->filterClear();
        $keyString = $this->_getKeyByArray($key);
        $pos = $this->_getItemByKeyString($keyString);
        if ($pos !== false)
        {
            return $this->current($pos);
        } else {
            $this->_currentItem = null;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function next()
    {
        if ($this->_position < count($this->_items))
        {
            $this->_position++;
        }
        return $this->current();
    }

    /**
     * debug method for visualizing the pack
     *
     * @return array
     */
    public function toArray()
    {
        if (empty($this->_items))
        {
            return [];
        }
        $result = $this->_getItemsRecursive($this->_items);
        return $result;
    }

    /**
     * @param int $deep
     * @param Item[] $items
     * @return array
     */
    private function _getItemsRecursive($items, $deep = 0)
    {
        $result = [];

        foreach ($items as $item)
        {
            $array = $item->getFields();
            $references = $item->getReferences();
            /**
             * @var string $name
             * @var ItemsPack $dataPack
             */
            foreach ($references as $name => $dataPack)
            {
                $array[$name] = $dataPack->toArray();
            }
            $result[] = $array;
        }

        return $result;
    }


}
