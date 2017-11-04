<?php

namespace app\controllers;

use app\components\Captcha;
use app\models\Feedback\FeedbackPack;
use app\models\ObjFactory;

class FeedbackController extends FOController
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $feedbackData = ObjFactory::request()->getBodyParams();
        $ip = ObjFactory::request()->getUserIP();
        $response = ObjFactory::request()->post("g-recaptcha-response");
        if (Captcha::validate($response, $ip))
        {
            $feedback = new FeedbackPack();
            $feedback->newItem();
            $feedback->ip = $ip;
            $feedback->referrer = ObjFactory::request()->getReferrer();
            if ($feedback->setBatch($feedbackData)->addItem()) {
                $feedback->flush();
                return json_encode(['result' => 'success', 'body' => $this->render('success')]);
            } else {
                return json_encode([
                    'result' => 'error',
                    'body' => $this->render('error')]);
            }
        } else {
            return json_encode([
                'result' => 'error',
                'message' => 'Пожалуйста, пройдите капчу',
                'body' => $this->render('error')]);
        }
    }
}
