<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 12:24 AM
 */

namespace app\models\Products;

use app\models\BO\ResampleImages;
use app\models\Category\Categories;
use app\models\Category\CategoryPack;
use app\models\Options\Options;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsPack;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesCollection;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesPack;
use app\models\ProductsOptions\ProductsOptions;
use app\models\ProductsOptions\ProductsOptionsCollection;
use app\models\ProductsOptions\ProductsOptionsDelete;
use app\models\ProductsOptions\ProductsOptionsEdit;
use app\models\ProductsOptions\ProductsOptionsPack;
use app\models\ProductsSpecs\ProductsSpecsCollection;
use app\models\ProductsSpecs\ProductsSpecsDelete;
use app\models\ProductsSpecs\ProductsSpecsEdit;
use app\models\ProductsSpecs\ProductsSpecsPack;
use app\models\Specs\Specs;
use app\models\Specs\SpecsCollection;
use app\models\Specs\SpecsPack;
use app\models\SpecsRestrictedValues\SpecsRestrictedValuesPack;
use Yii;

class ProductsEdit
{
    private $_productData = [];

    /**
     * CategoryEdit constructor.
     * @param $productData
     */
    public function __construct($productData)
    {
        //TODO: validate
        $this->_productData = $productData;
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $product = new ProductsPack();
        $product->setBatch($this->_productData);
        $product->addItem();
        $result = $product->flush();
        $result->last();
        $productId = $result->product_id;
        $this->saveOptions($productId);
        $this->saveSpecs($productId);
        return $result->product_id;
    }

    private function saveOptions($productId)
    {
        if (!empty($this->_productData['products_options'])) {
            $oldOptions = new ProductsOptionsCollection();
            $oldOptions->setProductIds($productId);
            $optionsDelete = new ProductsOptionsDelete($oldOptions->getIds());
            $optionsDelete->delete();
            $options = new ProductsOptionsEdit($this->_productData['products_options'], $productId);
            $options->save();
        }
    }

    private function saveSpecs($productId)
    {
        if (!empty($this->_productData['products_specs'])) {
            $oldSpecs = new ProductsSpecsCollection();
            $oldSpecs->setProductIds($productId);
            $specsDelete = new ProductsSpecsDelete($oldSpecs->getIds());
            $specsDelete->delete();
            $specs = new ProductsSpecsEdit($this->_productData['products_specs'], $productId);
            $specs->save();
        }
    }

    /**
     * @param $productId
     * @param CategoryPack $category
     */
    private static function _clearExtraSpecs($productId, $category)
    {
        $productSpecs = new ProductsSpecsCollection();
        $productSpecs->getModel()->loadSpecInfo();
        $productSpecs->setProductIds($productId);
        $productsSpecsPack = $productSpecs->getAll();
        $deleteArray = [];
        for($productsSpecsPack->first();$productsSpecsPack->current();$productsSpecsPack->next())
        {
            if ($productsSpecsPack->specs->category_id !== $category->category_id)
            {
                $deleteArray['product_id'][] = $productId;
                $deleteArray['spec_id'][] = $productsSpecsPack->spec_id;
            }
        }

        if (!empty($deleteArray))
        {
            $deleteArray['product_id'] = array_unique($deleteArray['product_id']);
            $deleteArray['spec_id'] = array_unique($deleteArray['spec_id']);
            $specsDelete = new ProductsSpecsDelete($deleteArray);
            $specsDelete->delete();
        }
    }

    /**
     * @param $productId
     * @param CategoryPack $category
     */
    private static function _clearExtraOptions($productId, $category)
    {
        $productsOptions = new ProductsOptionsCollection();
        $productsOptions->getModel()->loadOptionInfo();
        $productsOptions->setProductIds($productId);
        $productsOptionsPack = $productsOptions->getAll();
        $deleteArray = [];
        for($productsOptionsPack->first();$productsOptionsPack->current();$productsOptionsPack->next())
        {
            if ($productsOptionsPack->options->category_id !== $category->category_id)
            {
                $deleteArray['product_id'][] = $productId;
                $deleteArray['option_id'][] = $productsOptionsPack->option_id;
                $deleteArray['options_restricted_values_id'][] = $productsOptionsPack->options_restricted_values_id;
            }
        }

        if (!empty($deleteArray))
        {
            $deleteArray['product_id'] = array_unique($deleteArray['product_id']);
            $deleteArray['option_id'] = array_unique($deleteArray['option_id']);
            $deleteArray['options_restricted_values_id'] = array_unique($deleteArray['options_restricted_values_id']);
            $optionsDelete = new ProductsOptionsDelete($deleteArray);
            $optionsDelete->delete();
        }
    }

    /**
     * @param SpecsPack $specsPack
     * @param CategoryPack $category
     * @return SpecsPack
     */
    private static function _setSpecs($specsPack, $category)
    {
        $specsCollection = new SpecsCollection();
        $specsCollection->setCategoryIds([$category->category_id]);
        $specsCollection->setName($specsPack->name);
        $specsCollection->setLimit(1);
        $newSpecsPack = $specsCollection->getAll();
        if (!$newSpecsPack->first())
        {
            $newSpecsPack->newItem($specsPack);
            $newSpecsPack->spec_id = null;
            $newSpecsPack->category_id = $category->category_id;
            $newSpecsPack->addItem();
            $newSpecsPack = $newSpecsPack->flush();
        }

        return $newSpecsPack;
    }

    /**
     * @param OptionsPack $optionsPack
     * @param CategoryPack $category
     * @return OptionsPack
     */
    private static function _setOptions($optionsPack, $category)
    {
        $optionsCollection = new OptionsCollection();
        $optionsCollection->setCategoryIds([$category->category_id]);
        $optionsCollection->setName($optionsPack->name);
        $optionsCollection->setLimit(1);
        $newOptionsPack = $optionsCollection->getAll();
        if (!$newOptionsPack->first())
        {
            $newOptionsPack->newItem($optionsPack);
            $newOptionsPack->option_id = null;
            $newOptionsPack->category_id = $category->category_id;
            $newOptionsPack->addItem();
            $newOptionsPack = $newOptionsPack->flush();
        }
        return $newOptionsPack;

    }

    /**
     * @param SpecsRestrictedValuesPack $specsRestrictedValuesPack
     * @param SpecsPack $newSpecsPack
     * @return SpecsRestrictedValuesPack
     */
    private static function _setSpecsRestrictedValuesPack($specsRestrictedValuesPack, $newSpecsPack)
    {
        $newSpecsRestrictedValuesPack = new SpecsRestrictedValuesPack();
        for ($specsRestrictedValuesPack->first(); $specsRestrictedValuesPack->current(); $specsRestrictedValuesPack->next()) {
            $newSpecsRestrictedValuesPack->newItem($specsRestrictedValuesPack);
            $newSpecsRestrictedValuesPack->spec_id = $newSpecsPack->spec_id;
            $newSpecsRestrictedValuesPack->specs_restricted_values_id = null;
            $newSpecsRestrictedValuesPack->addItem();
        }
        return $newSpecsRestrictedValuesPack->flush();
    }

    /**
     * @param OptionsRestrictedValuesPack $optionsRestrictedValuesPack
     * @param OptionsPack $newOptionsPack
     * @return OptionsRestrictedValuesPack
     */
    private static function _setOptionsRestrictedValues($optionsRestrictedValuesPack, $newOptionsPack)
    {
        $optionsRestrictedValuesCollection = new OptionsRestrictedValuesCollection();
        $optionsRestrictedValuesCollection->setName($optionsRestrictedValuesPack->value);
        $optionsRestrictedValuesCollection->setOptionIds($newOptionsPack->option_id);
        $optionsRestrictedValuesCollection->setLimit(1);
        $newOptionsRestrictedValuesPack = $optionsRestrictedValuesCollection->getAll();
        if (!$newOptionsRestrictedValuesPack->first())
        {
            $newOptionsRestrictedValuesPack->newItem($optionsRestrictedValuesPack);
            $newOptionsRestrictedValuesPack->option_id = $newOptionsPack->option_id;
            $newOptionsRestrictedValuesPack->options_restricted_values_id = null;
            $newOptionsRestrictedValuesPack->addItem();
            $newOptionsRestrictedValuesPack = $newOptionsRestrictedValuesPack->flush();
        }
        return $newOptionsRestrictedValuesPack;
    }

    /**
     * @param array $specIds
     * @param int $productId
     * @param CategoryPack $category
     */
    private static function _moveSpecs($specIds, $productId, $category)
    {
        if (!empty($specIds)) {
            $specs = new Specs();
            $productSpecs = new ProductsSpecsCollection();
            $productSpecs->setProductIds($productId);
            $productsSpecsPack = $productSpecs->getAll();
            $newProductsSpecsPack = new ProductsSpecsPack();
            $specsPack = $specs->loadRestrictedValues()->getItemsByIds(['spec_id' => $specIds]);
            for ($specsPack->first(); $specsPack->current(); $specsPack->next()) {
                if ($productsSpecsPack->moveToItem(['product_id' => $productId, 'spec_id' => $specsPack->spec_id])) {
                    $newProductsSpecsPack->newItem($productsSpecsPack);
                    $specsRestrictedValuesPack = $specsPack->specs_restricted_values;
                    $newSpecsPack = static::_setSpecs($specsPack, $category);
                    $newProductsSpecsPack->spec_id = $newSpecsPack->spec_id;
                    $newSpecsRestrictedValuesPack = static::_setSpecsRestrictedValuesPack($specsRestrictedValuesPack, $newSpecsPack);
                    $newProductsSpecsPack->addItem();
                }
            }
            $newProductsSpecsPack->flush();
        }
        static::_clearExtraSpecs($productId, $category);
    }

    /**
     * @param array $optionIds
     * @param int $productId
     * @param CategoryPack $category
     */
    private static function _moveOptions($optionIds, $productId, $category)
    {
        if (!empty($optionIds)) {
            $options = new Options();
            $productsOptions = new ProductsOptionsCollection();
            $productsOptions->setProductIds($productId);
            $productsOptionsPack = $productsOptions->getAll();
            $newProductsOptionsPack = new ProductsOptionsPack();
            $optionsPack = $options->loadRestrictedValues()->getItemsByIds(['option_id' => $optionIds]);
            for ($optionsPack->first(); $optionsPack->current(); $optionsPack->next()) {
                $optionsRestrictedValuesPack = $optionsPack->options_restricted_values;
                if (!$optionsRestrictedValuesPack->first()) {
                    if ($productsOptionsPack->moveToItem([
                        'product_id' => $productId,
                        'option_id' => $optionsPack->option_id,
                        'options_restricted_values_id' => 0])) {

                        $newOptionsPack = static::_setOptions($optionsPack, $category);
                        $newProductsOptionsPack->newItem($productsOptionsPack);
                        $newProductsOptionsPack->option_id = $newOptionsPack->option_id;
                        $newProductsOptionsPack->options_restricted_values_id = 0;
                        $newProductsOptionsPack->addItem();
                    }
                } else {
                    for ($optionsRestrictedValuesPack->first(); $optionsRestrictedValuesPack->current(); $optionsRestrictedValuesPack->next()) {
                        if ($productsOptionsPack->moveToItem([
                            'product_id' => $productId,
                            'option_id' => $optionsPack->option_id,
                            'options_restricted_values_id' => $optionsRestrictedValuesPack->options_restricted_values_id])) {

                            $newProductsOptionsPack->newItem($productsOptionsPack);

                            $newOptionsPack = static::_setOptions($optionsPack, $category);
                            $newOptionsRestrictedValues = static::_setOptionsRestrictedValues($optionsRestrictedValuesPack, $newOptionsPack);

                            $newProductsOptionsPack->option_id = $newOptionsPack->option_id;
                            $newProductsOptionsPack->options_restricted_values_id = $newOptionsRestrictedValues->options_restricted_values_id;
                            $newProductsOptionsPack->addItem();
                        }
                    }
                }
            }
            $newProductsOptionsPack->flush();
        }
        static::_clearExtraOptions($productId, $category);
    }

    public static function moveFeatures($optionIds, $specIds, $productId, $categoryId)
    {
        $category = (new Categories())->getItemsByIds(['category_id' => [$categoryId]]);
        if ($category->moveToItem(['category_id' => $categoryId])) {
            static::_moveSpecs($specIds, $productId, $category);
            static::_moveOptions($optionIds, $productId, $category);
        }
    }
}