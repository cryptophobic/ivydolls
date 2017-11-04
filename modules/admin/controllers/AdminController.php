<?php

namespace app\modules\admin\controllers;

use app\models\ObjFactory;
use Yii;
use yii\web\Controller;
use app\modules\admin\helpers\HelperUrl;

class AdminController extends Controller
{
    /**
     * default layout
     *
     * @var string
     */
    public $layout = 'main';

    public $contentUrl = '';

}