<?php


namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
 * @package entities\shop\product
 * @property integer $characteristic_id
 * @property string $value
 */

class Value extends ActiveRecord
{
    public static function create($characteristicId, $value):self
    {
        $values = new static();
        $values->characteristic_id=$characteristicId;
        $values->value=$values;
        return $value;
    }

    public static function blank($characteristicId): self
    {
        $value = new static();
        $value->characteristic_id = $characteristicId;
        return $value;
    }

    public function change($value):void
    {
        $this->value=$value;
    }

    public function isForCharacteristic($id):bool
    {
        return $this->characteristic_id==$id;
    }

    public static function tableName(): string
    {
        return '{{%shop_values}}';
    }

}
