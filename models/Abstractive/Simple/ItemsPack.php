<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:49 AM
 */

namespace app\models\Abstractive\Simple;
use app\models\Options\OptionsPack;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesPack;

/**
 * Class ItemsPack
 * @package app\models\Abs
 */
abstract class ItemsPack extends ItemsIterator
{
    /**
     * @var array
     */
    protected $_fields = [];

    protected $_mandatory = [];

    protected $_references = [];

    /**
     * @var string|array
     */
    protected static $_primaryKey = '';

    protected static $_tableName = '';

    public function __construct()
    {
        if (empty(static::$_primaryKey) || empty(static::$_tableName))
        {
            throw new \Exception("PrimaryKey and TableName must be not empty");
        }
        parent::__construct();
    }

    protected function save()
    {
        return (new ItemStore($this))->save();
    }

    /**
     * TODO: make it static, maybe
     *
     * @return array
     */
    public function getFieldNames()
    {
        return array_keys($this->_fields);
    }

    /**
     * returns name(s) of primary key
     *
     * @return array
     */
    public static function getPrimaryKey()
    {
        return static::$_primaryKey;
    }

    public static function getTableName()
    {
        return static::$_tableName;
    }


    /**
     * @return ItemsPack
     */
    public function flush()
    {
        $result = $this->save();
        $this->clear();
        return $result;
    }

    /**
     * @param ItemsPack $dataPack
     * @return int
     */
    public function merge($dataPack)
    {
        $items = 0;
        $dataPack->filterClear();
        for($dataPack->first();$dataPack->current();$dataPack->next())
        {
            $items += (int)$this->newItem()->setBatch($dataPack->data())->addItem();
        }

        return $items;
    }

    /**
     * @param ItemsPack $newItem
     * @return $this
     */
    public function newItem($newItem = null)
    {
        $this->_currentItem = $this->_getNewItem();
        if (!empty($newItem))
        {
            $this->setBatch($newItem->data());
        }
        return $this;
    }

    public function getCurrentKey()
    {
        $key = [];
        $key[self::getPrimaryKey()[0]] = $this->_currentItem->get(static::getPrimaryKey());
        return $key;
    }

    public function filter($key, $value)
    {
        if(array_search($key, self::getPrimaryKey()) !== false)
        {
            return parent::filter($key, $value);
        }
        return false;
    }

    /**
     * @return string
     */
    protected function _getCurrentKeyString()
    {
        $key = $this->getCurrentKey();
        return $key[static::getPrimaryKey()[0]];
    }


    public function addItem()
    {
        if ($this->_getCurrentItem()->getFields())
        {
            $key = $this->getCurrentKey();
            $this->_addItem($key, $this->_currentItem);
            $this->_newItem = null;

            $this->first();
            return true;
        }
        return false;
    }

    /**
     * @return Item
     */
    private function _getNewItem()
    {
        if(empty($this->_newItem)) {
            $this->initializeReferences();
            $this->_newItem = new Item($this->_fields, $this->_mandatory, $this->_references);
        }
        return $this->_newItem;
    }

    /**
     * @return Item
     */
    protected function _getCurrentItem()
    {
        if (empty($this->_currentItem))
        {
            $this->_currentItem = $this->_getNewItem();
        }

        return $this->_currentItem;
    }

    /**
     * @param $values
     * @return $this
     */
    public function setBatch($values)
    {
        foreach ($values as $name => $value)
        {
            $this->set($name, $value);
        }
        return $this;
    }

    /**
     * @return bool
     */
    protected function initializeReferences()
    {
        return true;
    }

    /**
     * @param $name
     * @param $value
     * @return bool
     */
    protected function isProperType($name, $value)
    {
        return false;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public function set($name, $value)
    {
        $currentItem = $this->_getCurrentItem();
        if ($this->isProperType($name, $value))
        {
            return $currentItem->setReference($name, $value);
        } else {
            return $currentItem->set($name, $value);
        }
    }

    /**
     * @param $name
     * @return null
     */
    public function get($name)
    {
        if ($this->_currentItem)
        {
            return $this->_currentItem->get($name);
        }
        return null;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }
    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}