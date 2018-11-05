<?php

namespace forms\manage\shop\product;

use shop\entities\shop\product\Modification;
use yii\base\Model;

class ModificationForm extends Model
{
    public $code;
    public $name;
    public $price;

    public function __construct(Modification $modification, $config = [])
    {
        if($modification){
            $this->code=$modification->code;
            $this->name=$modification->name;
            $this->price=$modification->price;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['code', 'name'], 'required'],
            [['price'], 'integer'],
        ];
    }

}
