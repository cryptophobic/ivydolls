<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:41 PM
 */

namespace app\models\ProductsImages;

use app\models\BO\ResampleImages;
use Yii;

class ProductsImagesEdit
{
    private $_productsImages = [];
    private $_productId = 0;

    /**
     * @param $productsImages
     * @param $productId
     */
    public function __construct($productsImages, $productId)
    {
        $this->_productsImages = $productsImages;
        $this->_productId = $productId;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (empty($this->_productsImages)) return false;
        $productsImagesPack = new ProductsImagesPack();

        foreach ($this->_productsImages as $image)
        {
            $productsImagesPack->setBatch($image);
            $productsImagesPack->product_id = $this->_productId;
            $productsImagesPack->addItem();
        }
        $productsImagesPack->flush();
        return true;
    }

    /**
     * @param string $filePath
     * @param ProductsImagesPack $productsImagesPack
     * @return ProductsImagesPack
     */
    public static function storeImage($filePath, $productsImagesPack)
    {
        try {

            $productId = $productsImagesPack->product_id;
        $dirName = yii::$app->params['productPath'].'/'.$productId;
        $fullDirName = yii::getAlias('@app').$dirName;
        if (!file_exists($fullDirName))
        {
            mkdir($fullDirName);
        }
        $res = scandir($fullDirName);

        $res = array_filter($res, function ($item){
            $item = str_replace('.jpg', '', $item);
            if (!is_numeric($item))
            {
                return false;
            }
            return true;
        });
        sort($res, SORT_NUMERIC);
        $last = array_pop($res);
        $last += 1;
        $imagePath = $dirName.'/'.$last.'.jpg';
        $thumbPath = $dirName.'/'.$last.'_thumb.jpg';
        $lowPath = $dirName.'/'.$last.'_low.jpg';

        var_dump($filePath);
        if (stripos($filePath, "http") === 0)
        {
                copy($filePath, yii::getAlias('@app') . $imagePath);
            var_dump(yii::getAlias('@app').$imagePath);
        } else {
            move_uploaded_file($filePath, yii::getAlias('@app') . $imagePath);
        }

        $resampler = new ResampleImages();

        $currentImage = Yii::getAlias('@app').$imagePath;
        $thumbImageFile = Yii::getAlias('@app').$thumbPath;
        $lowImageFile = Yii::getAlias('@app').$lowPath;
        $resampler->resample($currentImage, $thumbImageFile, 70, 105);
        $resampler->resample($currentImage, $lowImageFile, 700, 1050);

        $productsImagesPack->image_high = $imagePath;
        $productsImagesPack->image_thumb = $thumbPath;
        $productsImagesPack->image_low = $lowPath;
        }
        catch (\Exception $e)
        {
            //shit happens..
            return $productsImagesPack;
        }

        return $productsImagesPack;
    }

}