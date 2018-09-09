<?php

namespace forms\manage\shop\product;

use shop\entities\shop\Categories;
use shop\entities\shop\Characteristic;
use shop\entities\shop\product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;
use shop\forms\manage\shop\product\PhotosForm;
use shop\forms\manage\shop\TagForm;

/**
 * @property PriceForm $price
 * @property MetaForm $meta
 * @property CategoriesForm $categories
 * @property PhotosForm $photos
 * @property TagForm $tags
 * @property ValueForm[] $values
 */

class ProductCreateForm extends CompositeForm
{
   public $brandId;
   public $code;
   public $name;

   public function __construct(array $config = [])
   {
       $this->price = new PriceForm();
       $this->meta = new MetaForm();
       $this->categories= new CategoriesForm();
       $this->photos = new PhotosForm();
       $this->tags = new TagsForm();
       $this->values= array_map(function (Characteristic $characteristic){
           return new ValueForm($characteristic);
       }, Characteristic::find()->orderBy('sort')->all());

       parent::__construct($config);
   }

   public function rules() :array
   {
       return [
           [['brandId', 'code', 'name'], 'required'],
           [['code', 'name'], 'string', 'max' => 255],
           [['brandId'], 'integer'],
           [['code'], 'unique', 'targetClass' => Product::class],
       ];
   }

    protected function internalForms(): array
    {
        return ['price', 'meta','photos', 'categories', 'tags', 'values'];
    }
}