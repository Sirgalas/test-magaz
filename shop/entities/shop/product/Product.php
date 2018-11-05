<?php

namespace shop\entities\shop\product;

use entities\shop\product\Photo;
use entities\shop\product\RelatedAssignment;
use entities\shop\product\TagAssignment;
use forms\manage\shop\product\ProductCreateForm;
use Yii;
use shop\entities\shop\Brand;
use shop\entities\shop\Categories;
use shop\entities\behaviors\MetaBehavior;
use shop\entities\Meta;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use entities\shop\product\CategoryAssignment;
use yii\web\UploadedFile;

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
 * @property CategoryAssignment[] $categoryAssignments
 * @property Value[] $values
 * @property Photo[] $photos
 * @property TagAssignment[] $tagAssignments
 * @property RelatedAssignment[] $relatedAssignments
 * @property Modification[] $modifications
 */
class Product extends ActiveRecord
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

    public function edit($brandId, $code, $name, Meta $meta):void
    {
        $this->brand_id=$brandId;
        $this->code=$code;
        $this->name=$name;
        $this->meta=$meta;
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

    public function getCategoryAssignments(): ActiveQuery
    {
        return $this->hasMany(CategoryAssignment::class, ['product_id' => 'id']);
    }

    public function getValues(): ActiveQuery
    {
        return $this->hasMany(Value::class, ['product_id' => 'id']);
    }

    public function getPhoto():ActiveQuery
    {
        return $this->hasOne(Photo::class,['product_id'=>'id'])->orderBy('sort');
    }

    public function getTagAssignments():ActiveQuery
    {
        return $this->hasMany(TagAssignment::class,['product_id' => 'id']);
    }

    public function getRelatedAssignments(): ActiveQuery
    {
        return $this->hasMany(RelatedAssignment::class, ['product_id' => 'id']);
    }

    public function getModifications(): ActiveQuery
    {
        return $this->hasMany(Modification::class, ['product_id' => 'id']);
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

    public function behaviors(): array
    {
        return [
            MetaBehavior::class,
            [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'categoryAssignments',
                    'tagAssignments',
                    'relatedAssignments',
                    'values',
                    'modifications',
                    'photos'
                ],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT=>self::OP_ALL,
        ];
    }

    /**
     * @param $categoryId
     * categories
     */
    public function changeMainCategory($categoryId): void
    {
        $this->category_id = $categoryId;
    }

    public function assignCategory($id): void
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $assignment){
            if ($assignment->isForCategory($id)) {
                return;
            }
        }
        $assignments[]=CategoryAssignment::create($id);
        $this->categoryAssignments=$assignments;

    }

    public function revokeCategory($id)
    {
        $assignments = $this->categoryAssignments;
        foreach ($assignments as $assignment) {
            if ($assignment->isForCategory($id)) {
                unset($assignments[$id]);
                $this->categoryAssignments=$assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeCategories(){
        $this->categoryAssignments=[];
    }

    /**
     * @param $id
     * @param $value
     * Characteristic
     */
    public function setValue($id, $value): void
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                $val->change($value);
                $this->values = $values;
                return;
            }
        }
        $values[] = Value::create($id, $value);
        $this->values = $values;
    }

    public function getValue($id): Value
    {
        $values = $this->values;
        foreach ($values as $val) {
            if ($val->isForCharacteristic($id)) {
                return $val;
            }
        }
        return Value::blank($id);
    }

    /**
     * @param UploadedFile $file
     * photos
     */
    public function addPhoto(UploadedFile $file):void
    {
        $photo=$this->photos;
        $photo[]=Photo::create($file);
        $this->updatePhotos($photo);
    }

    public function removePhoto($id):void
    {
        $photos=$this->photos;
        foreach ($photos as $i =>$photo){
            if($photo->isIdEqualTo($id)){
                unset($photos[$i]);
                $this->updatePhotos($photos);
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function removePhotos(): void
    {
        $this->updatePhotos([]);
    }

    public function movePhotoUp($id):void
    {
        $photos=$this->photos;
        foreach ($photos as $i=>$photo){
            if($photo->isIdEqualTo($id)){
                if($prev = $photos[$i - 1] ?? null){
                    $photos[$i - 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    public function movePhotoDown($id):void
    {
        $photos=$this->photos;
        foreach ($photos as $i=>$photo){
            if($photo->isIdEqualTo($id)){
                if($prev = $photos[$i + 1] ?? null){
                    $photos[$i + 1] = $photo;
                    $photos[$i] = $prev;
                    $this->updatePhotos($photos);
                }
                return;
            }
        }
        throw new \DomainException('Photo is not found.');
    }

    private function updatePhotos(array $photos): void
    {
        foreach ($photos as $i => $photo) {
            $photo->setSort($i);
        }
        $this->photos = $photos;
    }

    /**
     * @param $id
     * tags
     */
    public function assignTag($id):void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $assignment){
           if($assignment->isForTag($id)){
               return;
           }
        }
        $assignments[]=TagAssignment::create($id);
        $this->tagAssignments=$assignments;
    }

    public function revokeTag($id):void
    {
        $assignments = $this->tagAssignments;
        foreach ($assignments as $i=>$assignment){
            unset($assignments[$i]);
            $this->tagAssignments=$assignments;
            return;
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function revokeTags():void
    {
        $this->tagAssignments = [];
    }

    /**
     * @param $id
     * related product
     */
    public function assignRelatedProduct($id):void
    {
        $assignments=$this->relatedAssiginments;
        foreach ($assignments as $assignment){
            if($assignment->isForProduct($id)){
                return;
            }
        }
        $assignments[]= RelatedAssignment::create($id);
        $this->relatedAssignments=$assignments;
    }

    public function revokeRelatedProduct($id):void
    {
        $assignments=$this->relatedAssiginments;
        foreach ($assignments as $i=>$assignment){
            if($assignment->isForProduct($id)){
                unset($assignments[$i]);
                $this->relatedAssiginments=$assignments;
                return;
            }
        }
        throw new \DomainException('Assignment is not found.');
    }

    public function getModification($id):Modification
    {
        foreach ($this->modifications as $modification){
            if($modification->isIdEqualTo($id)){
                return $modification;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function addModification($code, $name, $price): void
    {
        $modification=$this->modifications;
        foreach ($modification as $modification){
            if($modification->isCodeEqualTo($code)){
                throw new \DomainException('Modification already exists.');
            }
        }
        $modification[]=Modification::create($code, $name, $price);
        $this->modification=$modification;
    }

    public function editModification($id,$code, $name, $price):void
    {
        $modifications=$this->modifications;
        foreach ($modifications as $modification){
            if($modification->isCodeEqualTo($id)){
                $modification->edit($code, $name, $price);
                $this->modifications=$modification;
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

    public function removeModification($id):void
    {
        $modification=$this->modifications;
        foreach ($modification as $i=>$modification){
            if($modification->isIdEqualTo($id)){
                unset($modification[$i]);
                $this->modifications=$modification;
                return;
            }
        }
        throw new \DomainException('Modification is not found.');
    }

}
