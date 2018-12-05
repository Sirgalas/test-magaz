<?php

namespace shop\entities\shop;

use shop\forms\manage\shop\CharacteristicForm;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * Class Characteristic
 * @package entities\shop
 * @propery integer $id
 * @property string $name,
 * @property string $type,
 * @property  bool $required,
 * @property string $default,
 * @property string $variants_json
 * @property string $sort
 * @property string $variants
 */

class Characteristic extends ActiveRecord
{
     const TYPE_STRING = 'string';
     const TYPE_INTEGER='integer';
     const TYPE_FLOAT='float';

     public $variants;

     public static function create(CharacteristicForm $form,array  $variants): self
     {
         $characteristic=new static();
         $characteristic->name=$form->name;
         $characteristic->type=$form->type;
         $characteristic->required=$form->required;
         $characteristic->default=$form->default;
         $characteristic->sort=$form->sort;
         $characteristic->variants=$variants;

         return $characteristic;
     }

     public function edit(CharacteristicForm $form, array $variants):void
     {
         $this->name=$form->name;
         $this->type=$form->type;
         $this->required=$form->required;
         $this->default=$form->default;
         $this->sort=$form->sort;
         $this->variants=$variants;
     }

     public function isSelect()
     {
         return count($this->variants)>0;
     }

     public static function tableName()
     {
         return '{{%shop_characteristics}}';
     }

     public function afterFind():void
     {
         $this->variants= Json::decode($this->getAttribute('variants_json'));
     }

     public function beforeSave($insert)
     {
         $this->setAttribute('variants_json',Json::encode($this->variants));
         return parent::beforeSave($insert);
     }

     public function isString():bool
     {
         return $this->type==self::TYPE_STRING;
     }

     public function isInteger():bool
     {
         return $this->type==self::TYPE_INTEGER;
     }

     public function isFloat():bool
     {
         return $this->type==self::TYPE_FLOAT;
     }

}
