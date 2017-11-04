<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Favourites;

class FavouritesEdit
{
    private $_favouritesData = [];

    /**
     * UsersEdit constructor.
     * @param $favouritesData
     */
    public function __construct($favouritesData)
    {
        //TODO: validate
        $this->_favouritesData = $favouritesData;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $favourites = new FavouritesPack();
        $favourites->setBatch($this->_favouritesData);
        $favourites->addItem();
        $favourites->flush();
        return true;
    }
}