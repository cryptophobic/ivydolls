<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/9/2017
 * Time: 10:36 PM
 */

namespace app\models\Abstractive\Complex;


use app\models\Category\Categories;
use app\models\ObjFactory;
use app\models\ProductsImages\ProductsImagesPack;
use yii\db\Query;

abstract class ModelAccessor extends \app\models\Abstractive\Simple\ModelAccessor
{
    /**
     * @param array $data
     * @param string $mergedKey
     */
    protected function _mergeData(array $data, $mergedKey = '')
    {
        foreach ($data as $item) {
            $idName = $this->getIdName();
            if (!is_array($idName))
            {
                $idName = [$idName];
            }

            $id = [];
            foreach ($idName as $key) {
                if (!empty($item[$key])) {
                    $id[$key] = $item[$key];
                }
            }

            if ($mergedKey == '') {
                if (!$this->_items->moveToItem($id)) {
                    $this->_items->newItem();
                }
                $this->_items->setBatch($item)->addItem();
            } elseif (count($id) == 1) {
                reset($id);
                $key = key($id);
                $value = $id[$key];
                if ($this->_items->filter($key, $value)) {
                    for ($this->_items->first(); $this->_items->current(); $this->_items->next()) {
                        $dataPack = $this->_items->get($mergedKey);
                        if ($dataPack instanceof ItemsPack) {
                            $dataPack->newItem()->setBatch($item)->addItem();
                        }
                    }
                }
                $this->_items->filterClear();
                $this->_items->first();
            }
        }
    }


    /**
     * @param ItemsPack $data
     * @param string $mergedKey
     * @param array $keyMap
     */
    protected function _mergeDataPack($data, $mergedKey = '', $keyMap = [])
    {
        for ($data->first(); $data->current(); $data->next()) {

            if ($mergedKey == '') {
                $id = $data->getCurrentKey();
                if (!$this->_items->moveToItem($id)) {
                    $this->_items->newItem();
                }
                $this->_items->setBatch($data->data())->addItem();
            } else {

                $idName = (array)$this->getIdName();
                $fieldNames = (array)$data->getFieldNames();

                $fieldNames = $this->_mapKeys($keyMap, $fieldNames);

                $mergeKeys = array_intersect($idName, $fieldNames);
                $mergeKeys = array_values($mergeKeys);
                if (count($mergeKeys) == 0) {
                    $this->_mergeNotIndexedData($data, $mergedKey, $keyMap);
                } elseif (count($mergeKeys) == 1) {
                    $this->_mergeIndexedData($data, $mergedKey, $mergeKeys, $keyMap);
                }
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

        if ($this->_items->filter($key, $value)) {
            for ($this->_items->first(); $this->_items->current(); $this->_items->next()) {
                $dataPack = $this->_items->get($mergedKey);
                if ($dataPack instanceof \app\models\Abstractive\Simple\ItemsPack) {
                    $dataPack->newItem($data)->addItem();
                }
            }
        }
        $this->_items->filterClear();
        $this->_items->first();
    }

    /**
     * @param ItemsPack $data
     * @param $mergedKey
     */
    private function _mergeNotIndexedData($data, $mergedKey, $keyMap)
    {
        $fieldNames = (array)$data::getPrimaryKey();
        $fieldNames = $this->_mapKeys($keyMap, $fieldNames);
        $idName = (array)$this->_items->getFieldNames();
        $mergeKeys = array_intersect($idName, $fieldNames);
        $mergeKeys = array_values($mergeKeys);
        if (count($mergeKeys) == 1) {

            $key = $mergeKeys[0];
            $value = $data->get($this->_unmapKey($keyMap, $key));

            for ($this->_items->first(); $this->_items->current(); $this->_items->next()) {
                if ($this->_items->get($key) === $value) {
                    $dataPack = $this->_items->get($mergedKey);
                    if ($dataPack instanceof \app\models\Abstractive\Simple\ItemsPack) {
                        $dataPack->newItem($data)->addItem();
                    }
                }
            }
        }
    }

    /**
     * Load general info
     *
     * @return bool
     */
    protected function _loadGeneralInfo()
    {
        if (!empty($this->_ids))
        {
            $className = get_class($this->_items);
            $key = $className::getPrimaryKey();
            if (!is_array($key))
            {
                $key = [$key];
            }
            $query = new Query();
            $query->select($this->_items->getFieldNames())
                ->from($className::getTableName());
            foreach ($key as $keyPart)
            {
                $query->andWhere(["IN", $keyPart, $this->_ids[$keyPart]]);
            }

            $query = $this->orderBy($query);

            $data = $query->createCommand(ObjFactory::dbConnection())->queryAll();
            $this->_mergeData($data);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $ids
     * @return ItemsPack
     */
    public function getItemsByIds($ids)
    {
        if (!empty($ids[0]))
        {
            $className = get_class($this->_items);
            /** @var array $key */
            $key = $className::getPrimaryKey();
            $ids = [$key[0] => $ids];
        }

        $res = parent::getItemsByIds($ids);

        return $res;
    }

}