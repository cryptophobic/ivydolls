<?php

namespace app\controllers;

use app\components\Captcha;
use app\models\Feedback\FeedbackPack;
use app\models\ObjFactory;

class StaticController extends FOController
{

    /**
     * @return string
     */
    public function actionDelivery()
    {
        return json_encode(['body' => $this->render('delivery')]);
    }

    /**
     * @return string
     */
    public function actionContactPage()
    {
        return json_encode(['body' => $this->render('contactPage')]);
    }

}
