<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/9/2017
 * Time: 10:36 PM
 */

namespace app\models\Abstractive\Complex;


use app\models\ObjFactory;
use yii\db\Query;

abstract class ModelAccessor
{

    protected $_loaded = [];

    /**
     * @var \app\models\Abstractive\Complex\ItemsPack
     */
    protected $_items = null;

    protected $_options = [];

    protected $_ids = [];

    public function getIdName()
    {
        return $this->_items->getPrimaryKey();
    }

    public function getTableName()
    {
        return $this->_items->getTableName();
    }

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
     * @return array
     */
    protected function _loadFromDb()
    {
        $data = [];
        if (!empty($this->_ids)) {
            $className = get_class($this->_items);
            $key = $className::getPrimaryKey();
            if (!is_array($key)) {
                $key = [$key];
            }
            $query = new Query();
            $query->select($this->_items->getFieldNames())
                ->from($className::getTableName());
            foreach ($key as $keyPart) {
                $query->andWhere(["IN", $keyPart, $this->_ids[$keyPart]]);
            }

            $query = $this->orderBy($query);

            $data = $query->createCommand(ObjFactory::dbConnection())->queryAll();
        }
        return $data;
    }

    /**
     * Load general info
     *
     * @return bool
     */
    protected function _loadGeneralInfo()
    {
        if ($data = $this->_loadFromDb())
        {
            $this->_items->mergeData($data);
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
        $this->_ids = (array)$ids;
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