<?php

namespace entities\shop\product;


use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class Photo
 * @package entities\shop\product
 * @property integer $id
 * @property string $file
 * @property integer $sort
 */

class Photo extends  ActiveRecord
{
    public static function create(UploadedFile $file):self
    {
        $photo=new static();
        $photo->file=$file;
        return $photo;
    }

    public function setSort($sort):void
    {
        $this->sort=$sort;
    }

    public function isIdEqualTo($id):bool
    {
        return $this->id===$id;
    }

    public static function tableName()
    {
        return '{{%shop_photos}}';
    }

}
