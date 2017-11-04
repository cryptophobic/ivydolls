<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\BO\ResampleImages;
use yii\console\Controller;
use app\models\ObjFactory;
use yii\db\Query;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ImportController extends Controller
{
    const doll_path = '/hsphere/local/home/artsandolls/artsandolls.com.ua/web/images/dolls';

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionInit()
    {
        $resampler = new ResampleImages();
        $connection = ObjFactory::dbConnection();

        $connection->createCommand("TRUNCATE categories")->execute();
        $connection->createCommand("TRUNCATE brands")->execute();
        $connection->createCommand("TRUNCATE products_images")->execute();
        $connection->createCommand("TRUNCATE products")->execute();
        $connection->createCommand("TRUNCATE products_specs")->execute();
        $connection->createCommand("TRUNCATE specs")->execute();

        $connection->createCommand("INSERT INTO categories (name) VALUES ('Куклы')")->execute();
        $dollsId = $connection->getLastInsertID();
        $connection->createCommand("INSERT INTO brands (name,logo) VALUES ('LoongSoul', '/images/brands/loongsoul.jpg')")->execute();
        $loongSoulId = $connection->getLastInsertID();
        $connection->createCommand("INSERT INTO specs (name) VALUES ('Рост')")->execute();
        $heightId = $connection->getLastInsertID();
        $result = scandir(static::doll_path);
        foreach($result as $dir)
        {
            if ($dir === '.' || $dir === '..' )
            {
                continue;
            }
            $currentHeight = $dir;
            $dollsPath = static::doll_path.'/'.$currentHeight;
            $dolls = scandir($dollsPath);
            foreach ($dolls as $doll)
            {
                if ($doll === '.' || $doll === '..' )
                {
                    continue;
                }

                $connection->createCommand('INSERT INTO products (brand_id, category_id, name, price)
                    VALUES (:brandId, :catId, :name, :price)')
                    ->bindValues([':brandId' => $loongSoulId, ':catId' => $dollsId,
                        ':name' => ucfirst($doll), ':price' => $currentHeight * 12.5])
                    ->execute();
                $productId = $connection->getLastInsertID();
                var_dump($productId);
                $connection->createCommand('INSERT INTO products_specs (spec_id, product_id, value)
                    VALUES (:specsId, :productId, :value)')
                    ->bindValues([':specsId' => $heightId, ':productId' => $productId, ':value' => $currentHeight])
                    ->execute();

                $currentDoll = $doll;
                $imagesPath = $dollsPath."/".$currentDoll;
                $images = scandir($imagesPath);
                foreach($images as $image)
                {
                    if ($image === '.' || $image === '..'  || strpos($image, '_thumb') !== false|| strpos($image, '_low') !== false)
                    {
                        continue;
                    }
                    $currentImage = '/images/dolls/'.$currentHeight.'/'.$currentDoll.'/'.$image;
                    $thumbImage = $currentImage.'_thumb.jpg';
                    $lowImage = $currentImage.'_low.jpg';

                    $thumbImageFile = '/hsphere/local/home/artsandolls/artsandolls.com.ua/web'.$currentImage.'_thumb.jpg';
                    $lowImageFile = '/hsphere/local/home/artsandolls/artsandolls.com.ua/web'.$currentImage.'_low.jpg';
                    $thumbImageFile = $resampler->resample('/hsphere/local/home/artsandolls/artsandolls.com.ua/web'.$currentImage, $thumbImageFile, 70, 105);
                    $lowImageFile = $resampler->resample('/hsphere/local/home/artsandolls/artsandolls.com.ua/web'.$currentImage, $lowImageFile, 700, 1050);
                    $connection->createCommand('INSERT INTO products_images (product_id, image_thumb, image_low, image_high)
                    VALUES (:productId, :imageThumb, :imageLow, :imageHigh)')
                        ->bindValues([':productId' => $productId, ':imageThumb' => $thumbImage, ':imageLow' => $lowImage, ':imageHigh' => $currentImage])
                        ->execute();
                }
            }
        }

        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=625 WHERE value=80')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=555 WHERE value=73')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=485 WHERE value=69')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=455 WHERE value=68')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=445 WHERE value=63')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=435 WHERE value=62')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=415 WHERE value=58')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=400 WHERE value=45')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=385 WHERE value=42')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=315 WHERE value=40')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=150 WHERE value=26')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=490 WHERE value=71')->execute();
        $connection->createCommand('UPDATE products p JOIN products_specs ps ON p.product_id=ps.product_id SET p.price=450 WHERE value=66')->execute();

        //var_dump($result);
    }
}
