<?php

namespace app\models\Abstractive\Complex;
use app\models\ObjFactory;
use Yii;
use yii\db\Query;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/9/2017
 * Time: 9:57 PM
 */
abstract class Collection extends \app\models\Abstractive\Simple\Collection
{
    /**
     * @param Query $query
     * @return Query
     */
    private function orderBy($query)
    {
        $id = $this->getIdName();

        $order = '';

        if (!is_array($id))
        {
            $id = [$id];
        }

        foreach ($id as $key)
        {
            $order .= $order !== '' ? ',': '';
            $order .= $key .' desc';
        }
        return $query;
    }

    /**
     * TODO: SOLID violation
     * this class should be independent of db
     */
    public function getIds()
    {
        $query = new Query();
        $keys = $this->getIdName();
        if (!is_array($keys))
        {
            $keys = [$keys];
        }
        $query->select($keys)->from($this->getTableName());
        $query = $this->_applyFilters($query);
        $query = $this->getModel()->orderBy($query);
        //$query = $this->orderBy($query);

        $query->offset($this->_offset)
            ->limit($this->_limit);

        $command = $query->createCommand(ObjFactory::dbConnection());

        $ids = $command->queryAll();
        $result = [];
        foreach ($keys as $key)
        {
            $res = array_column($ids, $key);
            if (!empty($res)) {
                $result[$key] = array_column($ids, $key);
            }
        }

        return $result;
    }

    /**
     * @return ItemsPack
     */
    public function getNext()
    {
        $result = $this->getIds();
        $this->_offset += $this->_limit;
        $model = $this->getModel();
        $result = $model->getItemsByIds($result);
        return $result;
    }

    /**
     * @param int $offset
     * @return ItemsPack
     */
    public function getItems($offset = 0)
    {
        $this->_offset = $offset;
        return $this->getNext();
    }



    /**
     * @return ItemsPack
     */
    public function getAll()
    {
        $limit = $this->_limit;
        $this->_limit = yii::$app->params['recordLimit'];
        $itemsPack = $this->getItems();
        $this->_limit = $limit;
        return $itemsPack;
    }



}