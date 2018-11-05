<?php

namespace forms\manage\shop\product;

use shop\entities\shop\product\Product;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CategoriesForm extends Model
{
    public $main;
    public $outher;

    public function __construct(Product $product=null, $config = [])
    {
        if($product){
            $this->main=$product->category_id;
            $this->outher=ArrayHelper::getColumn($product->categoryAssignments,'category_id');
        }
        parent::__construct($config);
    }

    public function rules():array
    {
        return [
            ['main', 'required'],
            ['main', 'integer'],
            ['others', 'each', 'rule' => ['integer']],
            ['others', 'default', 'value' => []]
        ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}
