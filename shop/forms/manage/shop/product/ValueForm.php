<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07.09.18
 * Time: 22:57
 */

namespace forms\manage\shop\product;

use yii\base\Model;
use shop\entities\shop\product\Value;
use shop\entities\shop\Characteristic;

/**
 * @property integer $id
 */

class ValueForm extends Model
{
    public $value;

    private $_characteristic;

    public function __construct(Characteristic $characteristic, Value $value=null, $config = [])
    {
        if($value)
            $this->value=$value;
        $this->_characteristic=$characteristic;
        parent::__construct($config);
    }

    public function rules():array
    {
        return array_filter([
            $this->_characteristic->required ? ['value', 'required'] : false,
            $this->_characteristic->isString() ? ['value', 'string', 'max' => 255] : false,
            $this->_characteristic->isInteger() ? ['value', 'integer'] : false,
            $this->_characteristic->isFloat() ? ['value', 'number'] : false,
            ['value', 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'value' => $this->_characteristic->name,
        ];
    }

    public function getId()
    {
        return $this->_characteristic->id;
    }

}
