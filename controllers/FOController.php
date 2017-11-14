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

    public function renderEmpty($view, $params = [])
    {
        $layout = $this->layout;
        $this->layout = false;
        $result = parent::render($view, $params);
        $this->layout = $layout;
        return $result;
    }


    public function render($view, $params = []) {
        if(Yii::$app->request->isAjax)
        {
            return $this->renderEmpty($view, $params);
        }
        return parent::render($view, $params);
    }
}
