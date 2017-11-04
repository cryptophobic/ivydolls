<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 9/10/2017
 * Time: 7:27 PM
 */

namespace app\models\ProductsImages;
use app\models\Abstractive\Complex\ItemsPack;

/**
 * Class ProductsImagesPack
 *
 * @property integer products_image_id
 * @property integer main
 * @property integer product_id
 * @property string image_thumb
 * @property string image_low
 * @property string image_high
 */
class ProductsImagesPack extends ItemsPack
{
    protected $_fields = [
        'products_image_id' => null,
        'product_id' => null,
        'image_thumb' => null,
        'image_low' => null,
        'image_high' => null,
        'main' => null,
    ];

    protected static $_primaryKey = ['products_image_id', 'product_id'];

    protected static $_tableName = 'products_images';

    protected $_mandatory = [
        'image_thumb' => true,
        'image_low' => true,
        'image_high' => true,
        'product_id' => true
    ];
}
