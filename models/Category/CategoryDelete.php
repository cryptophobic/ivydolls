<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Category;

use app\models\Abstractive\Simple\ItemsDelete;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsDelete;
use app\models\Specs\SpecsCollection;
use app\models\Specs\SpecsDelete;
use Yii;

class CategoryDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = CategoryPack::getTableName();
        $this->_idName = CategoryPack::getPrimaryKey();
        parent::__construct($ids);
    }

    private function deleteSpecs()
    {
        $specsCollection = new SpecsCollection();
        $specsCollection->setCategoryIds($this->_ids['category_id']);
        $specsCollection->setLimit(100);
        while ($ids = $specsCollection->getIds()) {
            $specsDelete = new SpecsDelete($ids);
            $specsDelete->delete();
        }
    }

    private function deleteOptions()
    {
        $optionsCollection = new OptionsCollection();
        $optionsCollection->setCategoryIds($this->_ids['category_id']);
        $optionsCollection->setLimit(100);
        while ($ids = $optionsCollection->getIds()) {
            $optionsGroups = new OptionsDelete($ids);
            $optionsGroups->delete();
        }
    }

    public function delete()
    {
        $this->deleteSpecs();
        $this->deleteOptions();

        $offset = 0;
        $step = 100;
        while ($array = array_slice((array)$this->_ids['category_id'], $offset, $offset+$step))
        {
            $category = new Categories();
            $items = $category->getItemsByIds($array);
            for($items->first();$items->current();$items->next())
            {
                $thumb = yii::getAlias('@app').'/web'.$items->image_thumb;
                if (file_exists($thumb))
                {
                    unlink($thumb);
                }
                $image = yii::getAlias('@app').'/web'.$items->image;
                if (file_exists($image))
                {
                    unlink($image);
                }
            }

            $offset += $step;
        }
        parent::delete();
    }

}