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
 * Class SelectedAccessor
 * @package app\models\Custom\Complex
 */
class SelectedAccessor {

    /**
     * @var Item
     */
    private $_item = null;
    /**
     * SelectedAccessor constructor.
     * @param Item $item
     */
    public function __construct($item = null)
    {
        if (!empty($item))
        {
            $this->setItem($item);
        }
    }

    public function __get($name)
    {
        if (!empty($this->_item))
        {
            return $this->_item->get($name);
        }
        return null;
    }

    /**
     * @param $item
     */
    public function setItem($item)
    {
        $this->_item = $item;
    }
}