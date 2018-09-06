<?php

namespace forms\manage\shop;

use entities\shop\Characteristic;
use yii\base\Model;

/**
 * Class CharacteristicForm
 * @package forms\manage\shop
 * @property string $name,
 * @property string $type,
 * @property  bool $required,
 * @property string $default,
 * @property string $textVariants;
 * @property string $sort
 **/
class CharacteristicForm extends Model
{
       public $name;
       public $type;
       public $required;
       public $default;
       public $textVariants;
       public $sort;

       private $_characteristic;

       public function __construct(Characteristic $characteristic=null, $config = [])
       {
           if($characteristic) {
               $this->name=$characteristic->name;
               $this->type=$characteristic->type;
               $this->required=$characteristic->required;
               $this->default=$characteristic->default;
               $this->textVariants=implode(PHP_EOL, $characteristic->variants);
               $this->sort=$characteristic->sort;
               $this->_characteristic=$characteristic;
           }else {
               $this->sort = Characteristic::find()->max('sort') + 1;
           }
           parent::__construct($config);
       }

    public function rules(): array
    {
        return [
            [['name', 'type', 'sort'], 'required'],
            [['required'], 'boolean'],
            [['default'], 'string', 'max' => 255],
            [['textVariants'], 'string'],
            [['sort'], 'integer'],
            [['name'], 'unique', 'targetClass' => Characteristic::class, 'filter' => $this->_characteristic ? ['<>', 'id', $this->_characteristic->id] : null]
        ];
    }

    public function getVariants(): array
    {
        return preg_split('[\r\n]+#i', $this->textVariants);
    }
}
