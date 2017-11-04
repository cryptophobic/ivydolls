<?php

namespace app\controllers;

use app\components\Captcha;
use app\models\Feedback\FeedbackPack;
use app\models\ObjFactory;

class AuthorizeController extends FOController
{

    /**
     * @return string
     */
    public function actionLoginForm()
    {
        if (ObjFactory::webUser()->getUserData()->current())
        {
            return json_encode(['body' => $this->render('authorized', ['user' => ObjFactory::webUser()->getUserData()])]);
        } else {
            return json_encode(['body' => $this->render('loginForm')]);
        }
    }

    public function actionLogin()
    {
        $authorized = ObjFactory::webUser()->getUserData()->current();
        if (!$authorized && ObjFactory::request()->method === "POST")
        {
            ObjFactory::webUser()->getUserData()->phone = ObjFactory::request()->post('data');
            ObjFactory::webUser()->getUserData()->email = ObjFactory::request()->post('data');
            ObjFactory::webUser()->getUserData()->password = ObjFactory::request()->post('password');
            $authorized = ObjFactory::webUser()->authorise();
        }
        return json_encode(['authorized' => $authorized]);
    }

    /**
     * @return string
     */
    public function actionRegisterForm()
    {
        if (ObjFactory::webUser()->getUserData()->current())
        {
            return json_encode(['body' => $this->render('authorized', ['user' => ObjFactory::webUser()->getUserData()])]);
        } else {
            return json_encode(['body' => $this->render('registerForm')]);
        }
    }

    /**
     * @return string
     */
    public function actionLogout()
    {
        return json_encode(['logout' => ObjFactory::webUser()->logout()]);
    }
}
