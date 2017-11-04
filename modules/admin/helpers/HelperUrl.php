<?php
namespace app\modules\admin\helpers;
use app\models\ObjFactory;
use Yii;
use yii\base\Controller;
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/5/2017
 * Time: 11:01 PM
 */
class HelperUrl
{
    /**
     * @param Controller $controller
     * @param string $action
     * @param array $params
     * @return string
     */
    public static function getCurrentActionUrl($controller, $action, $params)
    {
        $params = array_merge(["/admin/{$controller->id}/{$action}"], $params);
        return ObjFactory::urlManager()->createUrl($params);

        //return '/admin/init/?controller='.$controller->id.'?url='.urlencode($redirectUrl);

    }
    /**
     * @param Controller $controller
     * @param string $action
     * @param array $params
     * @return string
     */
    public static function getRedirectUrl($controller, $action, $params)
    {
        $redirectUrl = static::getCurrentActionUrl($controller, $action, $params);
        return '/admin/'.$controller->id.'/index/?url='.urlencode($redirectUrl);
    }
}