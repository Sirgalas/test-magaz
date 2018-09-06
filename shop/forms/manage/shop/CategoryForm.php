<?php

namespace forms\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Categories;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\validator\SlugValidator;

/**
 * Class CategoryForm
 * @package forms\manage\shop
 * @property  $name
 * @property $slug
 * @property $title
 * @property $description
 * @property $parentId;
 */

class CategoryForm extends CompositeForm
{
    public $name;
    public $slug;
    public $title;
    public $description;
    public $parentId;

    private $_category;

    public function __construct(Categories $category = null, $config=[])
    {
        if($category)
        {
            $this->name=$category->name;
            $this->slug=$category->slug;
            $this->title=$category->title;
            $this->description=$category->description;
            $this->parentId = $category->parent ? $category->parent->id : null;
            $this->meta = new MetaForm($category->meta);
            $this->_category = $category;
        }else{
            $this->meta= new MetaForm();
        }
        parent::__construct($config);
    }

    public function rules()
    {
          return[
              [['name', 'slug'], 'required'],
              [['parentId'], 'integer'],
              [['name', 'slug', 'title'], 'string', 'max' => 255],
              [['description'], 'string'],
              ['slug', SlugValidator::class],
              [['name', 'slug'], 'unique', 'targetClass' => Categories::class, 'filter' => $this->_category ? ['<>', 'id', $this->_category->id] : null]
       ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}
