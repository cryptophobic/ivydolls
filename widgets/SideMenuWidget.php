<?php

namespace app\widgets;

use app\models\Category\CategoryPack;
use yii\base\Widget;
use yii\helpers\Html;


/**
 * Created by PhpStorm.
 * User: dima
 * Date: 3/22/2017
 * Time: 1:48 AM
 */
class SideMenuWidget extends Widget
{
    /**
     * @var CategoryPack
     */
    public $categories = null;

    public function run()
    {
        return $this->render('sideMenu', ['categories' => $this->categories]);
    }
}