<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03.09.18
 * Time: 23:52
 */

namespace shop\helpers;

use shop\entities\shop\Characteristic;
use yii\helpers\ArrayHelper;

class CharacteristicHelper
{
     public static function typeList():array
     {
         return [
            Characteristic::TYPE_STRING=>'string',
            Characteristic::TYPE_INTEGER=>'integer',
            Characteristic::TYPE_FLOAT=>'float'
             ];
     }

     public static function typeName($type):string
     {
         return ArrayHelper::getValue(self::typeList(),$type);
     }

}
