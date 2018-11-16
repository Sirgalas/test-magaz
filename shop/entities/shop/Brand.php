<?php

namespace shop\entities\shop;

use shop\entities\Meta;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use shop\entities\behaviors\MetaBehavior;
/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property Meta $meta
 */

class Brand extends ActiveRecord
{
    public $meta;

    public static function create($name, $slug, Meta $meta):self
    {
       $brand=new static();
       $brand->name=$name;
       $brand->slug=$slug;
       $brand->meta=$meta;
       return $brand;
    }

    public function edit($name,$slug,Meta $meta):void
    {
        $this->name=$name;
        $this->slug=$slug;
        $this->meta=$meta;
    }

    public static function tableName(): string
    {
        return '{{%shop_brand}}';
    }

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
        ];
    }

}
