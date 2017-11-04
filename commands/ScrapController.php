<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\_system\ScrapLoongSoul;
use app\models\Products\Products;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ScrapController extends Controller
{

    private $_server = "http://www.ls-doll.com/en/";

    public function actionLoong()
    {
        for ($i = 1; $i < 7; $i++)
        {
            $scrap = new ScrapLoongSoul("http://www.ls-doll.com/en/category.php?id=12&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC", $this->_server);
            $scrap->start();
        }
    }
    public function actionLoongLimited()
    {
        for ($i = 1; $i < 11; $i++)
        {
            $url = "http://www.ls-doll.com/en/category.php?id=13&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->start();
        }
    }

    public function actionLoongBaby()
    {
        $url = "http://www.ls-doll.com/en/category.php?id=74";
        $scrap = new ScrapLoongSoul($url, $this->_server);
        $scrap->start();
    }

    public function actionLoongClothes()
    {
        for ($i = 1; $i < 12; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=15&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(105);
            $scrap->start();
        }
    }

    public function actionLoongShoes()
    {
        for ($i = 1; $i < 3; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=17&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(106);
            $scrap->start();
        }
    }

    public function actionLoongWigs()
    {
        for ($i = 1; $i < 10; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=16&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(107);
            $scrap->start();
        }
    }

    public function actionLoongAccessories()
    {
        for ($i = 1; $i < 4; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=47&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(111);
            $scrap->start();
        }
    }
    public function actionLoongEyes()
    {
        for ($i = 1; $i < 6; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=6&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(116);
            $scrap->start();
        }
    }

    public function actionLoongOther()
    {
        for ($i = 1; $i < 4; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=30&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(115);
            $scrap->start();
        }
    }

    public function actionLoongBody()
    {
        for ($i = 1; $i < 3; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=31&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(114);
            $scrap->start();
        }
    }

    public function actionLoongHead()
    {
        for ($i = 1; $i < 9; $i++) {

            $url = "http://www.ls-doll.com/en/category.php?id=29&price_min=0&price_max=0&page=$i&sort=last_update&order=DESC";
            $scrap = new ScrapLoongSoul($url, $this->_server);
            $scrap->setCategory(113);
            $scrap->start();
        }
    }

    public function actionLoongProduct($id)
    {
        $url = 'http://www.ls-doll.com/en/goods.php?id='.$id;
        $scrap = new ScrapLoongSoul($url, $this->_server);
        $scrap->productPage($url);
    }

    public function actionIvyProduct($id)
    {
        $product = new Products();
        $productPack = $product->getItemsByIds(['product_id' => [$id]]);

        $url = $productPack->original_url;
        $scrap = new ScrapLoongSoul($url, $this->_server);
        $scrap->productPage($url, $id);
    }

}
