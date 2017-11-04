<?php

namespace app\models\Abstractive\Simple;
use app\models\ObjFactory;
use yii\db\Query;

/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/9/2017
 * Time: 9:57 PM
 */
abstract class Collection
{
    /**
     * @var ModelAccessor
     */
    protected $_model = null;

    protected $_filter = [];

    protected $_keyword = '';

    protected $_limit = 12;

    protected $_offset = 0;

    /**
     * @param string $keyword
     * @return $this
     */
    public function setKeyWord($keyword)
    {
        $this->_keyword = $keyword;
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->_limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @param Query $query
     * @return Query
     */
    protected function _applyFilters($query)
    {
        return $query;
    }

    /**
     * TODO: SOLID violation
     * this class should be independent of db
     */
    public function getIds()
    {
        $query = new Query();

        $query->select($this->getIdName())->from($this->getTableName());

        $query = $this->_applyFilters($query);

        $query = $this->getModel()->orderBy($query);

        $query
            ->offset($this->_offset)
            ->limit($this->_limit);


        $command = $query->createCommand(ObjFactory::dbConnection());

        return $command->queryColumn();
    }

    /**
     * @return \app\models\Abstractive\Complex\ModelAccessor
     */
    public abstract function getModel();

    /**
     * @return string|array
     */
    public function getIdName()
    {
        return $this->getModel()->getIdName();
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->getModel()->getTableName();
    }
}