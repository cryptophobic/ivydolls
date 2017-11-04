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
    protected $_options = [
        '_loadSpecs' => false,
        '_loadOptions' => false
    ];

    protected function initialize()
    {
        $this->_items = new CategoryPack();
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
            $this->_mergeDataPack($specs, 'specs');
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

            $this->_mergeDataPack($options, 'options');
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