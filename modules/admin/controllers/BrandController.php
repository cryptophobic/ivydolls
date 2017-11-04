<?php

namespace app\modules\admin\controllers;

use app\models\Brands\Brands;
use app\models\Brands\BrandsCollection;
use app\models\Brands\BrandsDelete;
use app\models\Brands\BrandsEdit;
use app\models\Brands\BrandsPack;
use app\models\ObjFactory;

/**
 * Description of DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class BrandController extends AdminController
{
    /**
     * init all url in admin
     * @return null
     */
    public function actionIndex($offset = 0)
    {
        $collection = new BrandsCollection();
        $brands = $collection->getItems($offset);
        $newOffset = $collection->getOffset();
        return $this->render('index', ['brands' => $brands]);

    }

    public function actionBrand($brandId = 0)
    {
        if (!empty($brandId))
        {
            $brand = new Brands();
            $items = $brand->getItemsByIds($brandId);
            $items->moveToItem($brandId);
            $brand = $items;
        } else {
            $brand = new BrandsPack();
        }
        return $this->render('brand', ['brand' => $brand]);
    }

    public function actionDelete()
    {
        $brandIds = ObjFactory::request()->post('brandIds', []);
        foreach($brandIds as $brandId) {
            $model = new BrandsDelete(['brand_id' => $brandId]);
            $model->delete();
        }
        $this->redirect(ObjFactory::urlManager()->createUrl(['/admin/brand']));
    }

    public function actionUpload()
    {
        $brandData = ObjFactory::request()->getBodyParams();
        $brand = new BrandsPack();
        $brand->newItem()->setBatch($brandData);
        $brandId = $brand->brand_id;
        if ((int) $brandId && !empty($_FILES['brandImage']['tmp_name']))
        {
            $brand = BrandsEdit::storeImage($_FILES['brandImage']['tmp_name'], $brand);
        }

        $brand->addItem();
        $result = $brand->flush();
        if ($result->last() == true) {
            $brandId = $result->brand_id;
            $params = array_merge(['/admin/brand/brand'], ['brandId' => $brandId]);
            $this->redirect(ObjFactory::urlManager()->createUrl($params));
        } else {
            $this->redirect(ObjFactory::urlManager()->createUrl(['/admin/brand']));
        }
    }
}