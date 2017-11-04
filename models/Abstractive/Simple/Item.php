<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/19/2017
 * Time: 9:45 PM
 */

namespace app\models\Abstractive\Simple;
use app\models\ProductsOptions\ProductsOptionsPack;


/**
 * Class Item
 * @package app\models\Abs
 */
class Item
{
    /**
     * @var array
     */
    private $_fields = [];

    private $_mandatory = [];

    private $_references = [];

    /**
     * Item constructor.
     * @param array $fields
     * @param array $mandatory
     * @param array $references
     */
    public function __construct(array $fields, array $mandatory, array $references)
    {
        $this->_fields = $fields;
        $this->_mandatory = $mandatory;
        $this->_references = $references;
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function set($name, $value)
    {
        if (key_exists($name, $this->_fields)) {
            return $this->_fields[$name] = $value;
        } else {
            return null;
            //throw new \Exception("property $name is undefined");
        }
    }

    /**
     * @param $name
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function setReference($name, $value)
    {
        if (key_exists($name, $this->_references)) {
            return $this->_references[$name] = $value;
        } else {
            return null;
            //throw new \Exception("property $name is undefined");
        }
    }

    public function get($name)
    {
        if (key_exists($name, $this->_fields)) {
            return $this->_fields[$name];
        } elseif (key_exists($name, $this->_references)) {
            return $this->_references[$name];
        } else {
            throw new \Exception("property $name is undefined");
        }
    }

    /**
     * @return array
     */
    public function getFields()
    {
        $fieldsFilters = array_filter($this->_fields);
        $fieldsIntersected = array_intersect_key($fieldsFilters, $this->_mandatory);
        if (count($fieldsIntersected) == count($this->_mandatory))
        {
            return $this->_fields;
        }
        return [];
    }

    /**
     * @return array
     */
    public function getReferences()
    {
        $fieldsFilters = array_filter($this->_fields);
        $fieldsIntersected = array_intersect_key($fieldsFilters, $this->_mandatory);

        if (count($fieldsIntersected) == count($this->_mandatory))
        {
            return $this->_references;
        }
        return [];
    }
}