<?php


namespace app\modules\admin;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Description of Module
 *
 * @author Dima Uglach <DmitryUglach@gmail.com>
 * @since 1.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    public $layout = 'main';
    public $response = \yii\web\Response::FORMAT_HTML;
    public $defaultRoute = 'dolls';

    /**
     * @inheritdoc
     */
    public function init()
    {
        \Yii::$app->response->format = $this->response;
        parent::init();
    }

    /**
     * @param \yii\web\Application $app
     */
    public function bootstrap($app)
    {

    }


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
}
