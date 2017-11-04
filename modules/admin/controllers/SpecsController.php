<?php

namespace app\modules\admin\controllers;

use app\models\ObjFactory;
use app\models\Specs\SpecsCollection;
use app\models\Specs\SpecsDelete;
use app\models\Specs\SpecsEdit;
use app\models\Specs\SpecsPack;
use app\models\SpecsRestrictedValues\SpecsRestrictedValuesDelete;
use app\models\SpecsRestrictedValues\SpecsRestrictedValuesEdit;
use Yii;
use app\modules\admin\models;

/**
 * Description of DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SpecsController extends AdminController
{
    /**
     * init all url in admin
     * @param $categoryId
     * @return null
     */
    public function actionIndex($categoryId)
    {
        $collection = new SpecsCollection();
        $collection->getModel()->loadRestrictedValues();
        $collection->setCategoryIds($categoryId);
        $items = $collection->getItems();
        return $this->render('index', [
            'items' => $items,
            'categoryId' => $categoryId
        ]);
    }

    public function actionSave($categoryId)
    {
        $specsRestrictedValues = ObjFactory::request()->post('specs_restricted_values', []);
        $specs = ObjFactory::request()->post('specs', []);
        if ($specs) {
            $specsEdit = new SpecsEdit($specs);
            $specsEdit->save();
        }
        if ($specsRestrictedValues) {
            $specsRestrictedValuesEdit = new SpecsRestrictedValuesEdit($specsRestrictedValues);
            $specsRestrictedValuesEdit->save();
        }
        $params = array_merge(['/admin/specs'], ['categoryId' => $categoryId]);
        $this->redirect(ObjFactory::urlManager()->createUrl($params));
    }

    public function actionDelete($categoryId)
    {
        $specIds = ObjFactory::request()->post('specIds', []);
        $specsRestrictedIds = ObjFactory::request()->post('specsRestrictedIds', []);
        foreach($specIds as $specId) {
            $model = new SpecsDelete(['spec_id' => [$specId]]);
            $model->delete();
            if (!empty($specsRestrictedIds[$specId]))
            {
                unset($specsRestrictedIds[$specId]);
            }
        }

        foreach($specsRestrictedIds as $specId => $specsRestrictedValues) {
            if (!empty($specsRestrictedValues)) {
                $model = new SpecsRestrictedValuesDelete(['specs_restricted_values_id' => $specsRestrictedValues]);
                $model->delete();
            }
        }

        $this->redirect(ObjFactory::urlManager()->createUrl(array_merge(['/admin/specs'], ['categoryId'=> $categoryId])));
    }

}