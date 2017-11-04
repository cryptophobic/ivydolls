<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/9/2017
 * Time: 10:36 PM
 */

namespace app\models\Abstractive\Simple;

use yii\db\Query;
use app\models\ObjFactory;

abstract class ModelAccessor
{
    public function getIdName()
    {
        return $this->_items->getPrimaryKey();
    }

    public function getTableName()
    {
        return $this->_items->getTableName();
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
            $primaryKey = $className::getPrimaryKey();
            $primaryKey = $primaryKey[0];
            $query = new Query();
            $query->select($this->_items->getFieldNames())
                ->from($className::getTableName());
            $query->andWhere(["IN", $primaryKey, $this->_ids[$primaryKey]]);
            $data = $query->createCommand(ObjFactory::dbConnection())->queryAll();
            $this->_mergeData($data);
            return true;
        } else {
            return false;
        }
    }
    
    protected $_loaded = [];

    /**
     * @var \app\models\Abstractive\Complex\ItemsPack
     */
    protected $_items = null;

    protected $_options = [];

    protected $_ids = [];

    public final function __construct()
    {
        $this->initialize();
        if ($this->_items == null)
        {
            throw new \Exception("_items not initialized");
        }
    }

    /**
     * @return mixed
     */
    protected abstract function initialize();

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (key_exists('_'.$name, $this->_options))
        {
            $this->_options['_'.$name] = count($arguments) == 1?$arguments[0]:true;
            return $this;
        }
        throw new \Exception("method $name is not implemented");
    }

    /**
     * @param $entity
     * @return bool
     */
    protected function load($entity)
    {
        // because it is not possible to call isset for const
        $result = false;
        if (!$this->isLoaded($entity)) {
            if (method_exists($this, $entity)) {
                $result = $this->$entity();
                $this->_loaded[$entity] = true;
            }
        }
        return $result;
    }

    /**
     * @param $entity
     * @return bool
     */
    protected function isLoaded($entity)
    {
        return !empty($this->_loaded[$entity]);
    }

    /**
     * @param array $data
     * @param string $mergedKey
     */
    protected function _mergeData(array $data, $mergedKey = '')
    {
        foreach ($data as $item)
        {
            $id = $item[$this->getIdName()];
            if ($mergedKey == '')
            {
                if (!$this->_items->moveToItem($id))
                {
                    $this->_items->newItem();
                }
                $this->_items->setBatch($item)->addItem();
            } else {
                if ($this->_items->moveToItem($id))
                {
                    $dataPack = $this->_items->get($mergedKey);
                    if ($dataPack instanceof ItemsPack)
                    {
                        $dataPack->setBatch($item)->addItem();
                    }
                }
            }
        }
    }

    /**
     * @param $ids
     * @return \app\models\Abstractive\Complex\ItemsPack
     */
    public function getItemsByIds($ids)
    {
        if (!is_array($ids))
        {
            $ids = [$ids];
        }

        $this->_ids = $ids;

        $this->_items->clear();

        $this->_loadGeneralInfo();

        foreach ($this->_options as $option => $set)
        {
            if ($set === true)
            {
                $this->load($option);
            }
        }

        $this->_items->first();

        return $this->_items;
    }

    /**
     * @param Query $query
     * @return Query
     */
    public function orderBy($query)
    {
        $query->orderBy("updated desc");
        return $query;
    }
}