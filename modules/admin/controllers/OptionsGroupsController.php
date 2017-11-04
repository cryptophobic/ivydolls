<?php

namespace app\modules\admin\controllers;

use app\models\Category\Categories;
use app\models\Category\ProductsCollection;
use app\models\Category\CategoryPack;
use app\models\ObjFactory;
use app\models\OptionsGroups\OptionsGroups;
use app\models\OptionsGroups\OptionsGroupsCollection;
use app\models\OptionsGroups\OptionsGroupsDelete;
use app\models\OptionsGroups\OptionsGroupsEdit;
use app\models\OptionsGroups\OptionsGroupsPack;
use Yii;
use app\modules\admin\models;

class OptionsGroupsController extends AdminController
{
    /**
     * init all url in admin
     * @param $categoryId
     * @return null
     */
    public function actionIndex($categoryId)
    {
        $collection = new OptionsGroupsCollection();
        $collection->setCategoryIds($categoryId);
        $items = $collection->getItems();
        return $this->render('index', [
                'categoryId' => $categoryId,
                'items' => $items]
        );
    }

    public function actionDelete($categoryId)
    {
        $groupIds = ObjFactory::request()->post('groupIds', []);
        $model = new OptionsGroupsDelete($groupIds);
        $model->delete();
        $this->redirect(ObjFactory::urlManager()->createUrl(array_merge(['/admin/options-groups'], ['categoryId'=> $categoryId])));
    }

    public function actionSave($categoryId)
    {
        $id = ObjFactory::request()->post("save", false);
        $post = ObjFactory::request()->post($id, false);
        if ($post) {
            $optionsGroup = new OptionsGroupsEdit([$post], $categoryId);
            $optionsGroup->save();
        }
        $params = array_merge(['/admin/options-groups'], ['categoryId' => $categoryId]);
        $this->redirect(ObjFactory::urlManager()->createUrl($params));
    }
}