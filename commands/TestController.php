<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Abstractive\Simple\ItemsPack;
use app\models\Category\CategoryDelete;
use app\models\Category\CategoryEdit;
use app\models\ObjFactory;
use app\models\Options\OptionsCollection;
use app\models\Products\Products;
use app\models\Products\ProductsCollection;
use app\models\Specs\SpecsCollection;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TestController extends Controller
{

    public function actionTestProduct()
    {
        $doll = new Products();
        $doll->loadSpecs()->loadOptions()->loadBrandInfo()->loadImages();
        $product = $doll->getItemsByIds(['product_id' => [59]]);
        $options = new OptionsCollection();
        $optionsPack = $options->getItems();
        $specs = new SpecsCollection();
        $specs->setCategoryIds($product->category_id);
        $specsPack = $specs->getItems();
    }

    /**
     *
     */
    public function actionCategory()
    {
        $connection = ObjFactory::dbConnection();
        $connection->createCommand("DELETE FROM categories WHERE category_id>1;DELETE FROM specs WHERE spec_id>1;TRUNCATE specs_restricted_values;TRUNCATE options_groups;TRUNCATE options_restricted_values; TRUNCATE options;")->execute();

        $category = [
            'name' => 'test_category',
            'description' => 'test_description',
            'image' => 'test_image',
            'specs' => [
                [
                    'name' => 'test_spec',
                    'specs_restricted_values' => [
                        [
                            'value' => 'test_value'
                        ]
                    ]
                ]
            ],
            'options' => [
                [
                    'name' => 'test_option',
                    'description' => 'test_description',
                    'price' => '10',
                    'options_restricted_values' => [
                        [
                            'value' => 'test_value',
                            'price' => '5'
                        ]
                    ]
                ],
                [
                    'name' => 'test_option2',
                    'description' => 'test_description2',
                    'price' => '10',
                ]
            ]
        ];

        $categoryEdit = new CategoryEdit($category);
        $categoryEdit->save();
    }

    public function actionProduct()
    {
        $pack = new ProductsCollection();
        $pack->getModel()->loadSpecs()->loadImages()->loadBrandInfo()->loadOptions();
        $result = $pack->getItems();
        $result->toArray();

        $this->_printData($result);

    }

    /**
     * @param ItemsPack $dataPack
     */
    private function _printData($dataPack, $deep = 0)
    {
        for ($dataPack->first();$dataPack->current();$dataPack->next())
        {
            $input = "";
            foreach($dataPack->fields() as $field => $value)
            {
                print_r(str_pad($input, $deep, "\t"));
                print_r($field. "=>".$value."\n");
            }
            print_r("\n");
            foreach ($dataPack->data() as $field => $data)
            {
                if ($data instanceof ItemsPack)
                {
                    $this->_printData($data, $deep+1);
                }
            }
        }
    }

    public function actionDelete($cat)
    {
        $categoryDelete = new CategoryDelete(['category_id' => $cat]);
        $categoryDelete->delete();
    }

    public function actionIteration()
    {
        $collection = new ProductsCollection();
        $items = $collection->getItems();

        var_dump($items->first());
        var_dump($items->category_id);
        var_dump($items->next());
        var_dump($items->next());
        var_dump($items->next());
        var_dump($items->category_id);

    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionResample()
    {
        // задание максимальной ширины и высоты
        $width = 700;
        $height = 1050;

        $whiteImage = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate ( $whiteImage, 255, 255, 255 );
        imagefilledrectangle($whiteImage,0,0,$width,$height,$bg);

        ini_set('memory_limit', '128M');
        $filename = '/hsphere/local/home/artsandolls/artsandolls.com.ua/web/images/test/1.jpg';


        list($width_orig, $height_orig) = getimagesize($filename);

        $ratio_orig = $width_orig / $height_orig;
        $src_width = $width;
        $src_height = $height;


        if ($width < $width_orig || $height < $height_orig) {
            if ($width / $height > $ratio_orig) {
                $src_width = $height * $ratio_orig;
            } else {
                $src_height = $width / $ratio_orig;
            }
        }

        $x = ($width  - $src_width) / 2;
        $y = ($height  - $src_height) / 2;
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($whiteImage, $image, $x, $y, 0, 0, $src_width, $src_height, $width_orig, $height_orig);

// вывод
        imagejpeg($whiteImage, '/hsphere/local/home/artsandolls/artsandolls.com.ua/web/images/test/3.jpg', 100);
    }
}
