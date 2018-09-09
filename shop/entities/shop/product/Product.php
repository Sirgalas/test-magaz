<?php

namespace shop\entities\shop\product;

use yii\db\ActiveRecord;

/**
* @property integer $id
* @property string $name
* @property string $category_id
 * @property int $price_new
 * @property int $price_old
 * @property $tagAssignments
 * @property $brand_id
 * @property $code
 * @property $meta
 */

class Product extends ActiveRecord
{

    public static function create($name,$slug):self
    {
        $tag=new static();
        $tag->name=$name;
        $tag->slug=$slug;

        return $tag;
    }

    public function edit($name,$slug):void
    {
        $this->name=$name;
        $this->slug=$slug;
    }

    public static function tableName() : string
    {
        return '';
    }
    public function getValue($id){}

}
