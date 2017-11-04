<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 10/19/2017
 * Time: 2:02 AM
 */

namespace app\components;


use app\models\ObjFactory;
use Yii;

class Captcha
{

    public static function validate($captchaResponse, $userIp)
    {
        $secret = yii::$app->params['googleSecret'];

        $rest = ObjFactory::restComponent();
        $rest->SetServer("https://www.google.com/recaptcha/api/siteverify");
        if ($rest->CallApi("POST", ['secret' => $secret, 'response' => $captchaResponse, 'remoteip' => $userIp])) {
            $result = json_decode($rest->getLastResponse(), true);
        } else {
            return false;
        }
        return $result['success'];
    }
}