<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 5:23 PM
 */

namespace app\models\OptionsGroups;
use app\models\Options\OptionsEdit;

/**
 * @deprecated
 * Class OptionsGroupsEdit
 * @package app\models\OptionsGroups
 */
class OptionsGroupsEdit
{
    private $_optionsGroups = [];

    private $_categoryId = 0;

    /**
     * OptionsGroupsEdit constructor.
     * @param $optionGroups
     * @param $categoryId
     */
    public function __construct($optionGroups, $categoryId)
    {
        //TODO: validate
        $this->_optionsGroups = $optionGroups;
        $this->_categoryId = $categoryId;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        if (empty($this->_optionsGroups)) return false;
        $optionsGroupPack = new OptionsGroupsPack();

        foreach ($this->_optionsGroups as $optionGroup)
        {
            $optionsGroupPack->setBatch($optionGroup);
            $groupId = $optionsGroupPack->group_id;
            $optionsGroupPack->category_id = $this->_categoryId;
            $optionsGroupPack->addItem();

            if (!empty($optionGroup['options']))
            {
                if (empty($groupId)) {
                    $result = $optionsGroupPack->flush();
                    $result->last();
                    $groupId = $result->group_id;
                }
                $this->saveOptions($optionGroup['options'], $groupId);
            }
        }
        $optionsGroupPack->flush();
        return true;
    }

    private function saveOptions($options, $groupId)
    {
        $optionsEdit = new OptionsEdit($options, $groupId);
        $optionsEdit->save();
    }

}