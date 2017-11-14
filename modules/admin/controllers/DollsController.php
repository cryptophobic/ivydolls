<?php

namespace app\modules\admin\controllers;

use app\models\Brands\BrandsCollection;
use app\models\Category\Categories;
use app\models\Category\CategoryCollection;
use app\models\ObjFactory;
use app\models\Options\OptionsCollection;
use app\models\Products\ProductPresentation;
use app\models\Products\Products;
use app\models\Products\ProductsCollection;
use app\models\Products\ProductsDelete;
use app\models\Products\ProductsEdit;
use app\models\Products\ProductsPack;
use app\models\ProductsImages\ProductsImages;
use app\models\ProductsImages\ProductsImagesCollection;
use app\models\ProductsImages\ProductsImagesDelete;
use app\models\ProductsImages\ProductsImagesEdit;
use app\models\ProductsImages\ProductsImagesPack;
use app\models\ProductsRelated\ProductsRelatedDelete;
use app\models\ProductsRelated\ProductsRelatedPack;
use app\models\Specs\Specs;
use app\models\Specs\SpecsCollection;
use Yii;

/**
 * Description of DefaultController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DollsController extends AdminController
{
    /**
     * init all url in admin
     * @return null
     */
    public function actionIndex()
    {
        $limit = 10;

        $keyword = empty($_REQUEST['keyword'])?"":$_REQUEST['keyword'];
        $categoryId = empty($_REQUEST['categoryId'])?"112":$_REQUEST['categoryId'];
        $brandId = empty($_REQUEST['brandId'])?"0":$_REQUEST['brandId'];
        $height = empty($_REQUEST['height'])?"0":$_REQUEST['height'];
        $offset = empty($_REQUEST['offset'])?"0":$_REQUEST['offset'];

        if ($offset % $limit !== 0)
        {
            $offset = floor($offset / $limit) * $limit;
        }

        $collection = new ProductsCollection();
        $collection->setLimit($limit);
        $collection->setCategoryId($categoryId);
        $collection->getModel()->loadImages()->loadBrandInfo()->loadSpecs();

        if ($height > 0) {
            $collection->setHeight($height);
        }

        $categoriesCollection = new CategoryCollection();
        $categoriesCollection->setParentCategoryIds(-1);
        $categoriesCollection->getModel()->loadCategoryHierarchy();
        $categories = $categoriesCollection->getAll();

        //$categories->moveToItem(['category_id' => $categoryId]);
        //$categories->select();

        $brandsCollection = new BrandsCollection();
        $brands = $brandsCollection->getItems();

        if($brandId > 0) {
            $collection->setBrandIds($brandId);
            $brands->moveToItem(['brand_id' => $brandId]);
            $brands->select();
        }
        if (!empty($height)) {
            $collection->setHeight($height);
        }

        if(!empty($keyword))
        {
            $collection->setKeyWord($keyword);
        }

        $dolls = $collection->getItems($offset);
        $newOffset = $collection->getOffset();

        $nextUrl = ObjFactory::urlManager()->createUrl([
            '/admin/'.Yii::$app->controller->id . '/' . Yii::$app->controller->action->id,
            'categoryId' => $categoryId,
            'brandId' => $brandId,
            'keyword' => $keyword,
            'height' => $height,
            'offset' => $newOffset]);
        $prevUrl = ObjFactory::urlManager()->createUrl([
            '/admin/'.Yii::$app->controller->id . '/' . Yii::$app->controller->action->id,
            'categoryId' => $categoryId,
            'brandId' => $brandId,
            'keyword' => $keyword,
            'height' => $height,
            'offset' => $offset - $limit < 0 ? 0 : $offset - $limit]);
        $firstUrl = ObjFactory::urlManager()->createUrl([
            '/admin/'.Yii::$app->controller->id . '/' . Yii::$app->controller->action->id,
            'categoryId' => $categoryId,
            'brandId' => $brandId,
            'keyword' => $keyword,
            'height' => $height
        ]);
        return $this->render('index', [
            'selectedHeight' => $height,
            'keyword' => $keyword,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'brands' => $brands,
            'items' => $dolls,
            'nextUrl' => $nextUrl,
            'firstUrl' => $firstUrl,
            'prevUrl' => $prevUrl
            ]);

    }

    public function actionActivate($productsImageId, $productId)
    {
        $productImages = new ProductsImagesCollection();
        $productImages->setProductIds($productId);
        while (($productImagesPack = $productImages->getNext()) && ($productImagesPack->current() !== false))
        {
            for ($productImagesPack->first(); $productImagesPack->current();$productImagesPack->next())
            {
                if($productImagesPack->products_image_id == $productsImageId) {
                    $productImagesPack->main = 1;
                } else {
                    $productImagesPack->main = 0;
                }
            }
            $productImagesPack->flush();

        }
        return json_encode(['main' => $productsImageId]);
    }

    public function actionDelete()
    {
        $productIds = yii::$app->request->post('productIds', []);
        $productDelete = new ProductsDelete(['product_id' => $productIds]);
        $productDelete->delete();
        $this->redirect('/admin/dolls');
    }

    public function actionSave()
    {
        $productData = ObjFactory::request()->getBodyParams();
        $productEdit = new ProductsEdit($productData);
        $productId = $productEdit->save();


        if(!empty($productData['products_related']))
        {
            $productsRelatedPack = new ProductsRelatedPack();
            foreach ($productData['products_related'] as $productRelated)
            {
                $productsRelatedPack->newItem()->product_id = $productId;
                $productsRelatedPack->setBatch($productRelated);
                $productsRelatedPack->addItem();
            }
            $productsRelatedPack->flush();
        }

        if(!empty($productData['relatedDelete']))
        {
            $productsRelatedDelete = new ProductsRelatedDelete(['product_related_id' => $productData['relatedDelete']]);
            $productsRelatedDelete->delete();
        }

        if(!empty($productData['imagesDelete']))
        {
            //var_dump(array_merge($productData['imagesDelete'], ['product_id' => $productId]));exit;
            $productsImagesDelete = new ProductsImagesDelete(array_merge($productData['imagesDelete'], ['product_id' => [$productId]]));
            $productsImagesDelete->delete();
        }

        if ((int) $productId && !empty($_FILES['newImages']['tmp_name']))
        {
            $productsImages = new ProductsImagesPack();
            foreach ($_FILES['newImages']['tmp_name'] as $name) {
                if(!empty($name)) {
                    $productsImages->newItem();
                    $productsImages->product_id = $productId;
                    $productsImages = ProductsImagesEdit::storeImage($name, $productsImages);
                    $productsImages->addItem();
                }
            }
            $productsImages->flush();
        }

        $params = array_merge(['/admin/dolls/doll'], ['id' => $productId]);
        $this->redirect(ObjFactory::urlManager()->createUrl($params));
    }

    /**
     * init all url in admin
     * @return null
     */
    public function actionDoll($id)
    {
        $categories = new CategoryCollection();
        $categories->setParentCategoryIds(-1);
        $productPresentation = new ProductPresentation($id);

        if($productPresentation->getExtraSpecs()->current() || $productPresentation->getExtraProductOptions()->current())
        {
            return $this->render('extra', [
                'productPresentation' => $productPresentation,
                'categories' => $categories->getAll()
            ]);
        } else {
            return $this->render('doll', [
                'categories' => $categories->getAll(),
                'productPresentation' => $productPresentation
            ]);
        }
    }

    public function actionMove()
    {
        $productId = ObjFactory::request()->getBodyParam('product_id', 0);
        $product = (new Products())->getItemsByIds(['product_id' => [$productId]]);

        if (!$productId)
        {
            throw new \Exception("product_id is mandatory.". print_r(ObjFactory::request()->getBodyParams(), true));
        }

        if($newCategory = ObjFactory::request()->getBodyParam('newCategoryId', false))
        {

            $category = (new Categories())->getItemsByIds(['category_id' => [$newCategory]]);
            if ($product->moveToItem(['product_id' => $productId]) && $category->moveToItem(['category_id' => $newCategory]))
            {
                $product->category_id = $category->category_id;
                $product->flush();
            }
        } else {
            $specsIds = ObjFactory::request()->getBodyParam('specs', []);
            $optionsIds = ObjFactory::request()->getBodyParam('options', []);
            $categoryId = ObjFactory::request()->getBodyParam('category_id', 0);
            ProductsEdit::moveFeatures($optionsIds, $specsIds, $productId, $categoryId);
        }

        $params = array_merge(['/admin/dolls/doll'], ['id' => $productId]);
        $this->redirect(ObjFactory::urlManager()->createUrl($params));
    }

    public function actionNew($categoryId)
    {
        $categories = new Categories();

        return $this->render('doll', [
            'categories' => $categories->getItemsByIds(['category_id' => [$categoryId]]),
            'productPresentation' => new ProductPresentation(0, $categoryId)
        ]);
    }

}