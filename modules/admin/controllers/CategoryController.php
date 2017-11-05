<?php

namespace app\modules\admin\controllers;

use app\models\BO\ResampleImages;
use app\models\Category\Categories;
use app\models\Category\CategoryCollection;
use app\models\Category\CategoryDelete;
use app\models\Category\CategoryEdit;
use app\models\Category\CategoryPack;
use app\models\ObjFactory;
use Yii;
use app\modules\admin\models;

/**
 * Description of DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class CategoryController extends AdminController
{
    /**
     * init all url in admin
     * @return null
     */
    public function actionIndex($offset = 0, $parentCategoryId = 0)
    {
        $parentCategoryId = ObjFactory::request()->post('parentCategoryId', $parentCategoryId);
        $parentParentCategoryId = 0;
        if ($parentCategoryId > 0) {
            $categories = new Categories();
            $categoryPack = $categories->getItemsByIds(['category_id' => $parentCategoryId]);
            $parentParentCategoryId = $categoryPack->parent_category_id;
        }
        $collection = new CategoryCollection();
        $collection->setParentCategoryIds([$parentCategoryId, $parentParentCategoryId]);
        $categories = $collection->getItems($offset);
        $newOffset = $collection->getOffset();
        return $this->render('index', [
            'categories' => $categories,
            'parentCategoryId' => $parentCategoryId
        ]);

    }

    public function actionCategory($categoryId = 0, $parentCategoryId = 0)
    {
        if (!empty($categoryId))
        {
            $category = new Categories();
            $items = $category->getItemsByIds($categoryId);
            $items->moveToItem($categoryId);
            $category = $items;
        } else {
            $category = new CategoryPack();
            if (!empty($parentCategoryId))
            {
                $category->parent_category_id = $parentCategoryId;
            }
        }
        $categoriesCollection = new CategoryCollection();
        $categories = $categoriesCollection->getAll();

        return $this->render('category', [
            'category' => $category,
            'categories' => $categories
        ]);
    }

    public function actionDelete()
    {
        $categoryIds = ObjFactory::request()->post('categoryIds', []);
        foreach($categoryIds as $categoryId) {
            $model = new CategoryDelete(['category_id' => $categoryId]);
            $model->delete();
        }
        $this->redirect(ObjFactory::urlManager()->createUrl(['/admin/category']));
    }

    public function actionUpload()
    {
        $categoryData = ObjFactory::request()->getBodyParams();
        $category = new CategoryPack();
        $category->newItem()->setBatch($categoryData);
        $categoryId = $category->category_id;
        if ((int) $categoryId && !empty($_FILES['catImage']['tmp_name']))
        {
            $category = CategoryEdit::storeImage($_FILES['catImage']['tmp_name'], $category);
        }

        $category->addItem();
        $result = $category->flush();
        if ($result->last() == true) {
            $categoryId = $result->category_id;
            $params = array_merge(['/admin/category/category'], ['categoryId' => $categoryId]);
            $this->redirect(ObjFactory::urlManager()->createUrl($params));
        } else {
            $this->redirect(ObjFactory::urlManager()->createUrl(['/admin/category']));
        }
    }
}