<?php

namespace forms\manage\shop\product;

use shop\entities\shop\product\Product;
use shop\forms\manage\MetaForm;
use yii\base\Model;

class PriceForm extends Model
{
    public $old;
    public $new;

    public function __construct(Product $product=null, $config = [])
    {
        $this->old=$product->price_new;
        $this->new=$product->price_old;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['new'], 'required'],
            [['old', 'new'], 'integer', 'min' => 0],
        ];
    }

}
