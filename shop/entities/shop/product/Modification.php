<?php

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $price
 */
class Modification extends ActiveRecord
{
    /**
     * @param $code
     * @param $name
     * @param $price
     * @return Modification
     */
    public static function create($code, $name, $price):self
    {
        $modification = new static();
        $modification->code=$code;
        $modification->name=$name;
        $modification->price=$price;
        return $modification;
    }

    /**
     * @param $code
     * @param $name
     * @param $price
     */
    public function edit($code, $name, $price):void
    {
        $this->code=$code;
        $this->name=$name;
        $this->price=$price;
    }

    /**
     * @param $id
     * @return bool
     */
    public function isIdEqualTo($id):bool
    {
        return $this->id===$id;
    }

    /**
     * @param $code
     * @return bool
     */
    public function isCodeEqualTo($code):bool
    {
        return $this->code=$code;
    }

    public static function tableName(): string
    {
        return '{{%shop_modifications}}';
    }

}
