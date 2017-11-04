<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Session;

class SessionEdit
{
    private $_sessionData = [];

    /**
     * @param $sessionData
     */
    public function __construct($sessionData)
    {
        //TODO: validate
        $this->_sessionData = $sessionData;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $favourites = new SessionPack();
        $favourites->setBatch($this->_sessionData);
        $favourites->addItem();
        $favourites->flush();
        return true;
    }
}