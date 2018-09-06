<?php

namespace forms\product;

use shop\entities\shop\product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoriesForm extends Model
{
    public $main;
    public $outher;

    public function __construct(Product $product, $config = [])
    {
        parent::__construct($config);
    }
}
