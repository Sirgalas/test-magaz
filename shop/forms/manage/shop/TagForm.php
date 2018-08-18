<?php

namespace frontend\forms\manage\shop;

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
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', 'match', 'pattern' => '#^[a-z0-9_-]*$#s'],
            [['name', 'slug'], 'unique', 'targetClass' => Tags::class, 'filter' => $this->_tag ? ['<>', 'id', $this->_tag->id] : null]
        ];
    }

}
