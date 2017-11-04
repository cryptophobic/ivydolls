<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Category;

use app\models\BO\ResampleImages;
use app\models\Options\OptionsEdit;
use app\models\Specs\SpecsEdit;
use Yii;

class CategoryEdit
{
    private $_categoryData = [];

    /**
     * CategoryEdit constructor.
     * @param $categoryData
     */
    public function __construct($categoryData)
    {
        //TODO: validate
        $this->_categoryData = $categoryData;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $category = new CategoryPack();
        $category->setBatch($this->_categoryData);
        $category->addItem();
        $result = $category->flush();
        $result->last();
        $categoryId = $result->category_id;
        $this->saveOptions($categoryId);
        $this->saveSpecs($categoryId);
        return true;
    }

    private function saveOptions($categoryId)
    {
        if (!empty($this->_categoryData['options'])) {
            $optionGroups = new OptionsEdit($this->_categoryData['options'], $categoryId);
            $optionGroups->save();
        }
    }

    private function saveSpecs($categoryId)
    {
        if (!empty($this->_categoryData['specs'])) {
            $specs = new SpecsEdit($this->_categoryData['specs'], $categoryId);
            $specs->save();
        }
    }

    /**
     * @param string $filePath
     * @param CategoryPack $categoryPack
     * @return CategoryPack
     */
    public static function storeImage($filePath, $categoryPack)
    {
        $categoryId = $categoryPack->category_id;
        $imagePath = yii::$app->params['categoryPath'].'/'.$categoryId.'.jpg';
        $thumbPath = yii::$app->params['categoryPath'].'/'.$categoryId.'_thumb.jpg';
        move_uploaded_file ($filePath,
            yii::getAlias('@webroot').$imagePath);

        $resampler = new ResampleImages();

        $currentImage = Yii::getAlias('@webroot').$imagePath;
        $thumbImageFile = Yii::getAlias('@webroot').$thumbPath;
        $resampler->resample($currentImage, $thumbImageFile, 70, 105);
        $resampler->resample($currentImage, $currentImage, 700, 1050);
        $categoryPack->image_thumb = $thumbPath;
        $categoryPack->image = $imagePath;

        return $categoryPack;
    }
}