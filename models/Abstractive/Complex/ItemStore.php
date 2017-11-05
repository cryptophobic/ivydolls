<?php

namespace app\models\Abstractive\Complex;
use app\models\ObjFactory;

/**
 * Class ItemStore
 * @package app\models\Abstractive\Complex
 */
class ItemStore extends \app\models\Abstractive\Simple\ItemStore
{
    /**
     * @var ItemsPack
     */
    protected $_dataPack = null;

    /**
     * @var string|array
     */
    protected $_pkName = '';

    /**
     * @return ItemsPack
     */
    public function save()
    {
        for ($this->_dataPack->first(); $this->_dataPack->current(); $this->_dataPack->next())
        {
            $id = $this->insert($this->_dataPack);
            if (!empty($id) && count($this->_pkName) == 1)
            {
                $this->_dataPack->set($this->_pkName[0], $id);
            }
        }
        $dataPack = clone $this->_dataPack;
        $dataPack->first();
        return $dataPack;
    }

    /**
     * @param ItemsPack $item
     * @return int
     * @throws \yii\db\Exception
     */
    private function insert($item)
    {
        $values = $item->fields();

        $params = null;
        $sql = ObjFactory::dbConnection()->queryBuilder->insert($this->_tableName,
            $values, $params);
        $sql .= ' ON DUPLICATE KEY UPDATE ';
        $pack = [];
        foreach (array_keys($values) as $col) {
            $pack[] = "$col=VALUES($col)";
        }
        $sql .= implode(",", $pack);
        $res = ObjFactory::dbConnection()->createCommand($sql)->bindValues($params)->execute();
        if ($res > 0)
        {
            return ObjFactory::dbConnection()->getLastInsertID();
        }
        return false;
    }
}