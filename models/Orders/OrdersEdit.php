<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Orders;

class OrdersEdit
{
    private $_ordersData = [];

    /**
     * UsersEdit constructor.
     * @param $ordersData
     */
    public function __construct($ordersData)
    {
        //TODO: validate
        $this->_ordersData = $ordersData;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $orders = new OrdersPack();
        $orders->setBatch($this->_ordersData);
        $orders->addItem();
        $orders->flush();
        return true;
    }
}