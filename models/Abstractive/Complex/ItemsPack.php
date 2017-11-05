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

    /**
     * @param array $data
     * @param string $mergedKey
     */
    public function mergeData(array $data, $mergedKey = '')
    {
        foreach ($data as $item) {
            $idName = (array)static::getPrimaryKey();

            $id = [];
            foreach ($idName as $key) {
                if (!empty($item[$key])) {
                    $id[$key] = $item[$key];
                }
            }

            if ($mergedKey == '') {
                if (!$this->moveToItem($id)) {
                    $this->newItem();
                }
                $this->setBatch($item)->addItem();
            } elseif (count($id) == 1) {
                reset($id);
                $key = key($id);
                $value = $id[$key];
                if ($this->filter($key, $value)) {
                    for ($this->first(); $this->current(); $this->next()) {
                        $dataPack = $this->get($mergedKey);
                        if ($dataPack instanceof ItemsPack) {
                            $dataPack->newItem()->setBatch($item)->addItem();
                        }
                    }
                }
                $this->filterClear();
                $this->first();
            }
        }
    }


    /**
     * @param ItemsPack $data
     * @param string $mergedKey
     * @param array $keyMap
     */
    public function mergeDataPack($data, $mergedKey = '', $keyMap = [])
    {
        if ($mergedKey === '') {
            $this->merge($data);
        } else {
            for ($data->first(); $data->current(); $data->next()) {

                $idName = (array)static::getPrimaryKey();
                $fieldNames = (array)$data->getFieldNames();

                $fieldNames = $this->_mapKeys($keyMap, $fieldNames);

                $mergeKeys = array_intersect($idName, $fieldNames);
                $mergeKeys = array_values($mergeKeys);
                if (count($mergeKeys) == 0) {
                    $this->_mergeNotIndexedData($data, $mergedKey, $keyMap);
                } elseif (count($mergeKeys) == 1) {
                    $this->_mergeIndexedData($data, $mergedKey, $mergeKeys, $keyMap);
                }
                $this->filterClear();
                $this->first();
            }
        }
    }

    private function _mapKeys($keyMap, $fieldNames)
    {
        if (!empty($keyMap))
        {
            foreach ($fieldNames as $index => $key)
            {
                $fieldNames[$index] = $this->_mapKey($keyMap, $key);
            }
        }
        return $fieldNames;
    }

    private function _mapKey($keyMap, $key)
    {
        if (!empty($keyMap[$key]))
        {
            return $keyMap[$key];
        }
        return $key;
    }

    private function _unmapKey($keyMap, $key)
    {
        $keyMap = array_flip($keyMap);
        return $this->_mapKey($keyMap, $key);
    }

    /**
     * @param ItemsPack $data
     * @param $mergedKey
     * @param $mergeKeys
     * @param $keyMap
     */
    private function _mergeIndexedData($data, $mergedKey, $mergeKeys, $keyMap)
    {
        $key = $mergeKeys[0];
        $value = $data->get($this->_unmapKey($keyMap, $key));

        if ($this->filter($key, $value)) {
            for ($this->first(); $this->current(); $this->next()) {
                $dataPack = $this->get($mergedKey);
                if ($dataPack instanceof \app\models\Abstractive\Simple\ItemsPack) {
                    $dataPack->newItem($data)->addItem();
                }
            }
        }
    }

    /**
     * @param ItemsPack $data
     * @param $mergedKey
     * @param $keyMap
     */
    private function _mergeNotIndexedData($data, $mergedKey, $keyMap)
    {
        $fieldNames = (array)$data::getPrimaryKey();
        $fieldNames = $this->_mapKeys($keyMap, $fieldNames);
        $idName = (array)$this->getFieldNames();
        $mergeKeys = array_intersect($idName, $fieldNames);
        $mergeKeys = array_values($mergeKeys);
        if (count($mergeKeys) == 1) {
            $key = $mergeKeys[0];
            $value = $data->get($this->_unmapKey($keyMap, $key));

            for ($this->first(); $this->current(); $this->next()) {
                if ($this->get($key) === $value) {
                    $dataPack = $this->get($mergedKey);
                    if ($dataPack instanceof \app\models\Abstractive\Simple\ItemsPack) {
                        $dataPack->newItem($data)->addItem();
                    }
                }
            }
        }
    }


}