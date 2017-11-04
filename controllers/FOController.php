<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class FOController extends Controller
{

    public function init()
    {
        return parent::init();
    }


    public function render($view, $params = []) {
        if(Yii::$app->request->isAjax)
        {
            $this->layout = false;
            //$view = $view.'Ajax';
        }
        return parent::render($view, $params);
    }
}
