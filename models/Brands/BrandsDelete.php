<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Brands;

use app\models\Abstractive\Simple\ItemsDelete;
use app\models\Products\Products;
use Yii;

class BrandsDelete extends ItemsDelete
{
    protected $_ids = [];

    public function __construct($ids)
    {
        $this->_tableName = BrandsPack::getTableName();
        $this->_idName = BrandsPack::getPrimaryKey();
        parent::__construct($ids);
    }

    /**
     * TODO: todo?
     */
    private function deleteProducts()
    {
    }

    public function delete()
    {
        $this->deleteProducts();

        $offset = 0;
        $step = 100;
        while ($array = array_slice((array)$this->_ids['brand_id'], $offset, $offset+$step))
        {
            $brand = new Brands();
            $items = $brand->getItemsByIds($array);
            for($items->first();$items->current();$items->next())
            {
                $thumb = yii::getAlias('@app').'/web'.$items->logo_thumb;
                if (file_exists($thumb))
                {
                    unlink($thumb);
                }
                $image = yii::getAlias('@app').'/web'.$items->logo;
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