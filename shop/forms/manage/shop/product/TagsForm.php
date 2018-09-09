<?php

namespace forms\manage\shop\product;

use yii\base\Model;
use shop\entities\shop\product\Product;
use yii\helpers\ArrayHelper;

class TagsForm extends Model
{
    public $existing = [];
    public $textNew;

    public function __construct(Product $product=null, $config = [])
    {
        if($product)
            $this->existing=ArrayHelper::getValue($product->tagAssignments,'tag_id');
        parent::__construct($config);
    }

    public function rules()
    {
       return[
           ['existing', 'each', 'rule' => ['integer']],
           ['existing', 'default', 'value' => []],
           ['textNew', 'string'],
       ];
    }

    public function getNewNames():array
    {
         return array_filter(array_map('trim', preg_split('#\s*,\s*#i', $this->textNew)));
    }

}
