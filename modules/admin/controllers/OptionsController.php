<?php

namespace app\modules\admin\controllers;

use app\models\ObjFactory;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsDelete;
use app\models\Options\OptionsEdit;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesDelete;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesEdit;
use Yii;
use app\modules\admin\models;

/**
 * Description of DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class OptionsController extends AdminController
{
    /**
     * init all url in admin
     * @param $categoryId
     * @return null
     */
    public function actionIndex($categoryId)
    {
        $collection = new OptionsCollection();
        $collection->getModel()->loadRestrictedValues();
        $collection->setCategoryIds($categoryId);
        $items = $collection->getAll();
        return $this->render('index', [
            'items' => $items,
            'categoryId' => $categoryId
        ]);
    }

    public function actionSave($categoryId)
    {
        $optionsRestrictedValues = ObjFactory::request()->post('options_restricted_values', []);
        $options = ObjFactory::request()->post('options', []);
        if ($options) {
            $optionsEdit = new OptionsEdit($options);
            $optionsEdit->save();
        }
        if ($optionsRestrictedValues) {
            $optionsRestrictedValuesEdit = new OptionsRestrictedValuesEdit($optionsRestrictedValues);
            $optionsRestrictedValuesEdit->save();
        }
        $params = array_merge(['/admin/options'], ['categoryId' => $categoryId]);
        $this->redirect(ObjFactory::urlManager()->createUrl($params));
    }

    public function actionDelete($categoryId)
    {
        $optionIds = ObjFactory::request()->post('optionIds', []);
        $optionRestrictedIds = ObjFactory::request()->post('optionsRestrictedIds', []);
        foreach($optionIds as $optionId) {
            $model = new OptionsDelete(['option_id' => [$optionId]]);
            $model->delete();
            if (!empty($optionRestrictedIds[$optionId]))
            {
                unset($optionRestrictedIds[$optionId]);
            }
        }

        foreach($optionRestrictedIds as $optionId => $optionRestrictedValues) {
            if(!empty($optionRestrictedValues)) {
                $model = new OptionsRestrictedValuesDelete(['options_restricted_values_id' => $optionRestrictedValues]);
                $model->delete();
            }
        }

        $this->redirect(ObjFactory::urlManager()->createUrl(array_merge(['/admin/options'], ['categoryId'=> $categoryId])));
    }

}