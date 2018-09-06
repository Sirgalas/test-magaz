<?php

namespace shop\entities\shop;


use forms\manage\shop\CategoryForm;
use paulzi\nestedsets\NestedSetsBehavior;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use shop\entities\shop\queries\CategoryQuery;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "shop_categories".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property array $meta_json
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 */
class Categories extends ActiveRecord
{
    public $meta;

    public static function create(CategoryForm $form, Meta $meta) :self
    {
       $category = new static();
       $category->name=$form->name;
       $category->slug=$form->slug;
       $category->title=$form->title;
       $category->description=$form->description;
       $category->meta= $meta;
       return $category;
    }

    public function edit (CategoryForm $form, Meta $meta) :void
    {
       $this->name=$form->name;
       $this->slug=$form->slug;
       $this->title=$form->title;
       $this->description=$form->description;
       $this->meta=$meta;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'meta_json', 'lft', 'rgt'], 'required'],
            [['description'], 'string'],
            [['meta_json'], 'safe'],
            [['lft', 'rgt', 'depth'], 'integer'],
            [['name', 'slug', 'title'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'title' => 'Title',
            'description' => 'Description',
            'meta_json' => 'Meta Json',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
        ];
    }

    public function behaviors()
    {
        return [
            MetaBehavior::class,
            NestedSetsBehavior::class
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find(): CategoryQuery
    {
        return new CategoryQuery(static::class);
    }
}
