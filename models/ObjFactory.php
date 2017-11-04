<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 3:59 PM
 */

namespace app\models;

use app\components\RestComponent;
use app\components\WebUser;
use yii;
use yii\db\Connection;
use yii\web\Request;
use yii\web\Response;
use yii\web\UrlManager;

class ObjFactory
{
    /**
     * @return Connection
     */
    public static function dbConnection ()
    {
        return yii::$app->db;
    }

    /**
     * @return Request
     */
    public static function request()
    {
        return yii::$app->request;
    }

    /**
     * @return WebUser
     */
    public static function webUser()
    {
        return yii::$app->webUser;
    }

    /**
     * @return Response
     */
    public static function response()
    {
        return yii::$app->response;
    }

    /**
     * @return UrlManager
     */
    public static function urlManager ()
    {
        return yii::$app->urlManager;
    }

    /**
     * @return RestComponent
     */
    public static function restComponent ()
    {
        return yii::$app->restComponent;
    }
}