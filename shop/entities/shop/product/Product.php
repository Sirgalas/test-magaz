<?php

namespace shop\entities\shop\product;

use forms\manage\shop\product\ProductCreateForm;
use Yii;
use shop\entities\shop\Brand;
use shop\entities\shop\Categories;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "shop_products".
 *
 * @property int $id
 * @property int $category_id
 * @property int $brand_id
 * @property int $created_at
 * @property string $code
 * @property string $name
 * @property int $price_old
 * @property int $price_new
 * @property string $rating
 * @property string $meta
 * @property Brand $brand
 * @property Categories $category
 */
class Product extends \yii\db\ActiveRecord
{
    public $meta;

    public static function create(ProductCreateForm $form,Meta $meta)
    {
        $product=new static();
        $product->category_id=$form->categories->id;
        $product->brand_id=$form->brandId;
        $product->code=$form->code;
        $product->meta=$meta;
        $product->created_at = time();
        return $product;
    }

    public function setPrice($new, $old):void
    {
        $this->price_old=$old;
        $this->price_new=$new;
    }

    public function getBrand():ActiveQuery
    {
        return $this->hasOne(Brand::class,['id'=>'brand_id']);
    }

    public function getCategory():ActiveQuery
    {
        return $this->hasOne(Categories::class,['id'=>'category_id']);
    }



    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'brand_id', 'created_at', 'code', 'name'], 'required'],
            [['category_id', 'brand_id', 'created_at', 'price_old', 'price_new'], 'integer'],
            [['rating'], 'number'],
            [['code', 'name'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brand::class, 'targetAttribute' => ['brand_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'brand_id' => 'Brand ID',
            'created_at' => 'Created At',
            'code' => 'Code',
            'name' => 'Name',
            'price_old' => 'Price Old',
            'price_new' => 'Price New',
            'rating' => 'Rating',
        ];
    }

}
