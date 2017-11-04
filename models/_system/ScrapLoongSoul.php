<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 10/23/2017
 * Time: 10:55 AM
 */

namespace app\models\_system;


use app\models\ObjFactory;
use app\models\Options\OptionsCollection;
use app\models\Options\OptionsPack;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesCollection;
use app\models\OptionsRestrictedValues\OptionsRestrictedValuesPack;
use app\models\Products\Products;
use app\models\Products\ProductsCollection;
use app\models\Products\ProductsPack;
use app\models\ProductsImages\ProductsImages;
use app\models\ProductsImages\ProductsImagesEdit;
use app\models\ProductsImages\ProductsImagesPack;
use app\models\ProductsOptions\ProductsOptionsPack;
use app\models\ProductsRelated\ProductsRelatedPack;
use app\models\ProductsSpecs\ProductsSpecsPack;
use app\models\Specs\SpecsCollection;
use app\models\Specs\SpecsPack;
use Yii;

class ScrapLoongSoul
{
    private $_url = '';

    private $_server = '';

    private $_category = 112;

    /**
     * @var \app\components\RestComponent|null
     */
    private $_rest = null;

    public function __construct($url, $server)
    {
        require_once yii::getAlias('@app').'/components/simplehtmldom/simple_html_dom.php';
        $this->_rest = ObjFactory::restComponent();

        $this->_server = $server;
        $this->_url = $url;
    }

    public function setCategory($categoryId)
    {
        $this->_category = $categoryId;
    }

    public function start()
    {
        $dom = new \simple_html_dom();
        $dom->load_file($this->_url);
        $goods = $dom->find('div.goodsItem2');
        foreach ($goods as $product)
        {
            $a = $product->find('a',1);
            $url = $this->_server.$a->href;
            $this->productPage($url);
            //var_dump($title, $url);
        }
    }

    /*
 * http://www.ls-doll.com/en/goods.php?act=price&id=927&attr=7471,7475,7479,7486,7488,7490,7492,7494,7496
 * {"err_msg":"","result":"$415","qty":1}
 *
 */
    public function productPage($url, $productId = null)
    {
        var_dump($url);
        $dom = $this->_loadDom($url);

        $blocks = $dom->find('blockquote');
        $productOriginId = $dom->find('input[name=id]',0)->value;

        print_r("\n");
        $descImages = $blocks[1];

        $product = $this->_loadProduct($dom, $productOriginId, $productId, $url);
        $product = $this->_loadDesc($descImages, $product);
        $product->addItem();
        $product = $product->flush();
        $product->first();

        $productId = $product->product_id;

        print_r($productId."\n");

        $this->_loadImages($dom, $descImages, $product);
        $this->_loadOptions($dom, $product, $productOriginId);
        $this->_loadSpecs($blocks[0], $product);
        $this->_loadRelatedProducts($dom, $productId);

        print_r("\n");
    }

    /**
     * @param \simple_html_dom $dom
     * @param $productId
     */
    private function _loadRelatedProducts($dom, $productId)
    {
        $res = $dom->find('div.goodsItem3');
        $productCollection = new ProductsCollection();
        $productRelated = new ProductsRelatedPack();
        foreach ($res as $div)
        {
            $url = $div->find("a",0)->href;
            $url = $this->_server.$url;
            $productCollection->setOriginalUrls($url);
            $product = $productCollection->getAll();
            if ($product->current()) {
                var_dump($product->name);
                $productRelated->newItem()->product_id = $productId;
                $productRelated->product_related_id = $product->product_id;
                $productRelated->price = $product->price;
                $productRelated->addItem();
            }
        }
        $productRelated->flush();
    }

    /**
     * @param \simple_html_dom $descImages
     * @param ProductsPack $product
     * @return ProductsPack
     */
    private function _loadDesc($descImages, $product)
    {
        $descImages = preg_replace("/<img[^>]+>/i", "", $descImages);
        $descImages = preg_replace("/<\/?blockquote>/i", "", $descImages);
        $product->description = $descImages;
        return $product;
    }

    /**
     * @param $url
     * @return \simple_html_dom
     */
    private function _loadDom($url)
    {
        $dom = new \simple_html_dom();
        $dom->load_file($url);
        return $dom;
    }

    /**
     * @param \simple_html_dom $dom
     * @param \simple_html_dom $descImages
     * @param ProductsPack $product
     */
    private function _loadImages($dom, $descImages, $product)
    {
        $main = $dom->find("div.imgInfo", 0);
        $mainImage = $main->find('a', 0)->href;

        $images = $descImages->find('img');
        $productImagesPack = new ProductsImagesPack();
        foreach ($images as $image)
        {
            $productImagesPack->newItem();
            $productImagesPack->product_id = $product->product_id;
            $imageSrc = $image->src;
            if (stripos($imageSrc, "http") !== 0)
            {
                $imageSrc = $this->_server.$imageSrc;
            }
            $productImagesPack = ProductsImagesEdit::storeImage($imageSrc, $productImagesPack);
            $productImagesPack->addItem();
        }
        $productImagesPack->newItem();
        $productImagesPack->product_id = $product->product_id;
        if (stripos($mainImage, "http") !== 0)
        {
            $mainImage = $this->_server.$mainImage;
        }

        $productImagesPack = ProductsImagesEdit::storeImage($mainImage, $productImagesPack);
        $productImagesPack->addItem();

        $productImagesPack->flush();
    }

    /**
     * @param \simple_html_dom $dom
     * @param ProductsPack $product
     * @param $productOriginId
     */
    private function _loadOptions($dom, $product, $productOriginId)
    {
        $options = $dom->find('li[class="padd loop"]');

        $optionsCollection = new OptionsCollection();
        $optionsCollection->setCategoryIds($this->_category);
        $productOptionsPack = new ProductsOptionsPack();
        foreach ($options as $option)
        {
            $optionName = $option->find('strong', 0)->plaintext;
            $optionName = trim(str_replace([':'], '',$optionName ));
            $optionValues = $option->find('option');
            print $optionName ."(";
            foreach ($optionValues as $optionValue)
            {
                $value = $optionValue->plaintext;
                $value = preg_replace('/(\[.*\])/', '', $value);
                $value = preg_replace('/(plus.*$)/i', '', $value);
                $value = trim(preg_replace('/(\$.*)/i', '', $value));
                $id = $optionValue->value;
                $optPrice = 0;

                $this->_rest->SetServer($this->_server."/goods.php?act=price&id=$productOriginId&attr=$id");
                $i = 3;
                while ((!$res = $this->_rest->CallApi('GET')) && $i--) {}

                if ($res)
                {
                    $res = $this->_rest->getLastResponse();
                    $optPrice = $this->_getPrice($res) - $product->price;
                }

                $optionsCollection = new OptionsCollection();
                $optionsCollection->setCategoryIds($this->_category);
                $optionsCollection->setKeyWord($optionName);
                $optionsPack = $optionsCollection->getItems();

                if(!$optionsPack->current())
                {
                    $newOptionsPack = new OptionsPack();
                    $newOptionsPack->newItem();
                    $newOptionsPack->category_id = $this->_category;
                    $newOptionsPack->name = $optionName;
                    $newOptionsPack->price = $optPrice;
                    $newOptionsPack->addItem();
                    $optionsPack = $newOptionsPack->flush();
                    $optionsPack->first();
                }

                $optionsRestrictedValuesCollection = new OptionsRestrictedValuesCollection();
                $optionsRestrictedValuesCollection->setOptionIds($optionsPack->option_id);
                $optionsRestrictedValuesCollection->setKeyWord($value);
                $optionsRestrictedValuesPack = $optionsRestrictedValuesCollection->getItems();
                if (!$optionsRestrictedValuesPack->current())
                {
                    $newOptionsRestrictedValuesPack = new OptionsRestrictedValuesPack();
                    $newOptionsRestrictedValuesPack->newItem();
                    $newOptionsRestrictedValuesPack->value = $value;
                    $newOptionsRestrictedValuesPack->price = $optPrice;
                    $newOptionsRestrictedValuesPack->option_id = $optionsPack->option_id;
                    $newOptionsRestrictedValuesPack->addItem();
                    $optionsRestrictedValuesPack = $newOptionsRestrictedValuesPack->flush();
                    $optionsRestrictedValuesPack->first();
                }

                print "$id = $value = $optPrice,";
                $productOptionsPack->newItem();
                $productOptionsPack->option_id = $optionsPack->option_id;
                $productOptionsPack->price = $optPrice;
                $productOptionsPack->product_id = $product->product_id;
                $productOptionsPack->options_restricted_values_id = $optionsRestrictedValuesPack->options_restricted_values_id;
                $productOptionsPack->addItem();

                $productOptionsPack->flush();
            }
            print ")\n";
        }
    }

    /**
     * @param \simple_html_dom $dom
     * @param $productOriginId
     * @param $productId
     * @param $url
     * @return ProductsPack
     */
    private function _loadProduct($dom, $productOriginId, $productId, $url)
    {
        $product = new ProductsPack();

        $form = $dom->find('form[name=ECS_FORMBUY]', 0);
        $mpn = $form->find("dd", 0)->plaintext;
        $mpn = str_replace("NO.:", "", $mpn);
        $name = $form->find("p.f_l", 0)->plaintext;
        $name = trim(preg_replace('/[^\00-\255]+/u', ' ', $name));

        var_dump($name);
        $products = new Products();
        $foundProduct = $products->getDollsByNames($name);
        if ($foundProduct->current())
        {
            $product->newItem($foundProduct);
        } else {
            $product->newItem();
            $product->product_id = $productId;
        }

        $product->original_url = $url;
        $product->brand_id = 1;
        $product->category_id = $this->_category;



        $price = 0;
        $this->_rest->SetServer($this->_server. "/goods.php?act=price&id=$productOriginId&attr=");
        $i = 3;
        while ((!$res = $this->_rest->CallApi('GET')) && $i--) {}

        if ($res) {
            $res = $this->_rest->getLastResponse();
            $price = $this->_getPrice($res);
        }

        var_dump($price);

        $product->name = $name;
        $product->price = $price;
        $product->part_number = trim($mpn);
        return $product;
    }

    /**
     * @param \simple_html_dom $specs
     * @param ProductsPack $product
     */
    private function _loadSpecs($specs, $product)
    {
        $rows = $specs->find('tr');
        $specsCollection = new SpecsCollection();
        $specsCollection->setCategoryIds($this->_category);
        $productSpecsPack = new ProductsSpecsPack();
        foreach ($rows as $row)
        {
            $tds = $row->find("td");
            if (count($tds) == 2)
            {
                $specName = str_replace(['[',']'], '',$tds[0]->plaintext);
                $specName = trim(str_replace([':'], '',$specName));
                $specValue = trim($tds[1]->plaintext);
                if (strtolower($specName) !== 'gender')
                {
                    $specValue = preg_replace('/[^\00-\255]+/u', ' ', $specValue);
                }

                $specsCollection->setKeyWord($specName);
                $specsPack = $specsCollection->getItems();
                if ($specsPack->current() === false)
                {
                    $newSpecsPack = new SpecsPack();
                    $newSpecsPack->name = $specName;
                    $newSpecsPack->category_id = $this->_category;
                    $newSpecsPack->addItem();
                    $specsPack = $newSpecsPack->flush();
                    $specsPack->first();
                }

                $productSpecsPack->newItem();
                $productSpecsPack->spec_id = $specsPack->spec_id;
                $productSpecsPack->value = $specValue;
                $productSpecsPack->product_id = $product->product_id;
                $productSpecsPack->addItem();

                print_r("$specName => $specValue\n");
            }
        }
        $productSpecsPack->flush();

    }

    /**
     * @param $priceStr
     * @return float
     */
    private function _getPrice($priceStr)
    {
        $priceStr = json_decode($priceStr, 1);
        return (float)str_replace("$", '', $priceStr['result']);

    }
}