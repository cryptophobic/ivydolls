<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 3:07 PM
 */

namespace app\models\Abstractive\Simple;

use app\models\ObjFactory;

class ItemStore
{
    /**
     * @var string
     */
    protected $_tableName = '';

    /**
     * @var ItemsPack
     */
    protected $_dataPack = null;

    /**
     * @var string
     */
    protected $_pkName = '';

    /**
     * ItemStore constructor.
     * @param ItemsPack $dataPack
     */
    public function __construct($dataPack)
    {
        $this->_dataPack = $dataPack;
        $this->_tableName = $dataPack->getTableName();
        $this->_pkName = $dataPack->getPrimaryKey();
    }

    /**
     * @return ItemsPack
     */
    public function save()
    {
        for ($this->_dataPack->first(); $this->_dataPack->current(); $this->_dataPack->next())
        {
            $key = $this->_dataPack->get($this->_pkName);
            if (!empty($key))
            {
                $this->update($this->_dataPack);
            } else {
                $itemId = $this->insert($this->_dataPack);
                $this->_dataPack->set($this->_pkName, $itemId);
            }
        }
        $dataPack = clone $this->_dataPack;
        $dataPack->first();
        return $dataPack;
    }

    /**
     * @param ItemsPack $item
     * @return int
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    private function update($item)
    {
        return ObjFactory::dbConnection()->createCommand()
            ->update($this->_tableName, $item->fields(), [$this->_pkName => $item->get($this->_pkName)])->execute();
    }

    /**
     * @param ItemsPack $item
     * @return int
     * @throws \yii\db\Exception
     */
    private function insert($item)
    {
        ObjFactory::dbConnection()->createCommand()
            ->insert($this->_tableName, $item->fields())->execute();
        $id = ObjFactory::dbConnection()->getLastInsertID();
        return $id;
    }
}