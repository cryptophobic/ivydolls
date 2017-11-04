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
abstract class ItemsDelete
{
    /**
     * @var ModelAccessor
     */
    protected $_tableName = null;

    protected $_idName = null;

    protected $_ids = [];

    /**
     * ItemsDelete constructor.
     * @param int|array $ids
     * @throws \Exception
     */
    public function __construct($ids)
    {
        if (!is_array($ids))
        {
            $ids = [$ids];
        }
        foreach ($ids as $id)
        {
            if (empty($id))
            {
                throw new \Exception("ItemsDelete: need to provide not empty key" . print_r($ids));
            }
        }
        $this->_ids = $ids;
    }

    /**
     * @return int
     */
    public function delete ()
    {
        if (!empty($this->_tableName) && !empty($this->_idName) && !empty($this->_ids))
        {
            $condition = ['AND'];

            foreach ($this->_ids as $id => $values)
            {
                $condition[] = ["IN", $id, $values];
            }

            return ObjFactory::dbConnection()->createCommand()
                ->delete($this->_tableName,
                    $condition)->execute();
        }
        return 0;
    }

}