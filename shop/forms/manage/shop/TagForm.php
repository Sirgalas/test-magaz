<?php

namespace shop\forms\manage\shop;

use shop\validator\SlugValidator;
use yii\base\Model;
use shop\entities\shop\Tags;
/**
 * @property string $name
 * @property string $slug
 */

class TagForm extends Model
{

    public $name;
    public $slug;

    private $_tags;

    public function __construct(Tags $tags, $config = [])
    {
        if($tags)
        {
            $this->name=$tags->name;
            $this->slug=$tags->slug;
            $this->_tags=$tags;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', SlugValidator::class],
            [['name', 'slug'], 'unique', 'targetClass' => Tags::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]
        ];
    }

}
