<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:16 PM
 */

namespace app\models\Brands;

use app\models\BO\ResampleImages;
use Yii;

class BrandsEdit
{
    private $_brandsData = [];

    public function __construct($brandsData)
    {
        $this->_brandsData = $brandsData;
    }

    public function save()
    {
        if (empty($this->_brandsData)) return false;
        $brandsPack = new BrandsPack();

        foreach ($this->_brandsData as $brand) {
            $brandsPack->setBatch($brand);
            $brandsPack->addItem();
        }
        $brandsPack->flush();
        return true;
    }

    /**
     * @param string $filePath
     * @param BrandsPack $brandsPack
     * @return BrandsPack
     */
    public static function storeImage($filePath, $brandsPack)
    {
        $brandId = $brandsPack->brand_id;
        $imagePath = yii::$app->params['brandPath'].'/'.$brandId.'.jpg';
        $thumbPath = yii::$app->params['brandPath'].'/'.$brandId.'_thumb.jpg';
        $currentImage = Yii::getAlias('@webroot').$imagePath;
        $thumbImageFile = Yii::getAlias('@webroot').$thumbPath;
        move_uploaded_file ($filePath, $currentImage);

        $resampler = new ResampleImages();

        $resampler->resample($currentImage, $thumbImageFile, 70, 105);
        $resampler->resample($currentImage, $currentImage, 700, 1050);
        $brandsPack->logo_thumb = $thumbPath;
        $brandsPack->logo = $imagePath;

        return $brandsPack;
    }


}