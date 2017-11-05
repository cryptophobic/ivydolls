<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 6/5/2017
 * Time: 5:34 PM
 */

namespace app\models\Category;
use app\models\Abstractive\Complex\ModelAccessor;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsPack;
use app\models\Specs\SpecsCollection;
use app\models\Specs\SpecsPack;

/**
 * @method $this loadSpecs (bool $set = true)
 * @method $this loadOptions (bool $set = true)
 * @method CategoryPack getItemsByIds  ($ids)
 * @var CategoryPack _items
 *
 * Class Dolls
 * @package app\models\ProductCollection
 */
class Categories extends ModelAccessor
{
    private $_loadHierarchy = false;

    protected $_options = [
        '_loadSpecs' => false,
        '_loadOptions' => false,
    ];

    /**
     * @param bool $load
     * @return $this
     */
    public function loadCategoryHierarchy($load = true)
    {
        $this->_loadHierarchy = $load;
        return $this;
    }

    protected function initialize()
    {
        $this->_items = new CategoryPack();
    }

    /**
     * Load general info
     *
     * @return bool
     */
    protected function _loadGeneralInfo()
    {
        if ($this->_loadHierarchy && $categories = $this->_loadFromDb())
        {
            return $this->_hierarchiateCategories($categories);
        } else {
            return parent::_loadGeneralInfo();
        }
    }

    /**
     * @param CategoryPack $categoryPack
     * @param array $categories
     */
    private function _mergeCategories($categoryPack, $categories)
    {
        $categoryPack->mergeData($categories);

        foreach ($categories as $category)
        {
            if (!empty($category['categories']) && $categoryPack->moveToItem(['category_id' => $category['category_id']]))
            {
                $childrenPack = $this->_mergeCategories(new CategoryPack(), $category['categories']);
                $categoryPack->mergeDataPack($childrenPack, 'categories',
                    ['parent_category_id' => 'category_id']);

            }
        }
        return $categoryPack;
    }

    /**
     * @param array $categories
     */
    private function _hierarchiateCategories($categories)
    {
        $parentIds = [0];
        $orderedCategories = [];
        do {
            $result = array_filter($categories, function($category) use ($parentIds){
                return array_search($category['parent_category_id'], $parentIds) !== false;
            });
            if (!empty($result)) {
                $parentIds = array_column($result, 'category_id');
                $orderedCategories[] = array_combine($parentIds, $result);
            }
        } while (count($result) > 0);

        if (count($orderedCategories) > 1) {
            for ($i = count($orderedCategories) - 1; $i > 0; $i--) {
                foreach ($orderedCategories[$i] as $category)
                {
                    if (!empty($orderedCategories[$i-1][$category['parent_category_id']]))
                    {
                        if (empty($orderedCategories[$i-1][$category['parent_category_id']]['categories']))
                        {
                            $orderedCategories[$i-1][$category['parent_category_id']]['categories'] = [];
                        }
                        $orderedCategories[$i-1][$category['parent_category_id']]['categories'][] = $category;
                    }
                }
            }
        }

        $this->_items = $this->_mergeCategories(new CategoryPack(), $orderedCategories[0]);
        return $this->_items->first();
    }

    /**
     * Load specification
     *
     * @return bool
     */
    protected function _loadSpecs()
    {
        if (!empty($this->_ids))
        {
            $specsCollection = new SpecsCollection();
            $specsCollection->setCategoryIds($this->_ids);
            $specs = new SpecsPack();

            for (
                $specsPack = $specsCollection->getItems();
                $specsPack->current();
                $specsPack = $specsCollection->getNext())
            {
                $specs->merge($specsPack);
            }
            $this->_items->mergeDataPack($specs, 'specs');
            return true;
        } else {
            return false;
        }
    }

    /**
     * load products options
     *
     * @return bool
     */
    protected function _loadOptions()
    {
        if (!empty($this->_ids))
        {
            $optionsCollection = new OptionsCollection();
            $optionsCollection->setCategoryIds($this->_ids);
            $options = new OptionsPack();

            for ($optionsPack = $optionsCollection->getItems();
                $optionsPack->current();
                $optionsPack = $optionsCollection->getNext())
            {
                $options->merge($optionsPack);
            }

            $this->_items->mergeDataPack($options, 'options');
            return true;
        } else {
            return false;
        }
    }

    /**
     * returns all the parent categories of $categoryId
     *
     * @param int $categoryId
     * @return array
     */
    public static function getParentHierarchy($categoryId)
    {
        $categoryId = (array)$categoryId;
        $array = [];
        $categories = new Categories();
        do {
            $categoryPack = $categories->getItemsByIds(['category_id' => $categoryId]);
            $categoryId = [];
            for ($categoryPack->first();$categoryPack->current();$categoryPack->next())
            {
                $array[] = $categoryPack->category_id;
                if ($categoryPack->parent_category_id > 0)
                {
                    $categoryId[] = $categoryPack->parent_category_id;
                }
            }
        } while (count($categoryId) > 0);
        return $array;
    }
}