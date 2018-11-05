<?php


namespace shop\forms\manage\shop\product;

use forms\manage\shop\product\CategoriesForm;
use forms\manage\shop\product\PriceForm;
use forms\manage\shop\product\TagsForm;
use forms\manage\shop\product\ValueForm;
use yii\base\Model;
use shop\entities\shop\Characteristic;
use shop\entities\shop\product\Product;
use shop\forms\CompositeForm;
use shop\forms\manage\MetaForm;

class ProductEditForm  extends Model
{
    public $brandId;
    public $code;
    public $name;
    public $meta;
    public $tags;
    public $values;

    private $_product;

    /**
     * @property MetaForm $meta
     * @property PriceForm $price
     * @property MetaForm $meta
     * @property CategoriesForm $categories
     * @property PhotosForm $photos
     * @property TagsForm $tags
     * @property ValueForm[] $values
     * @property  string $code;
     * @property string $name
     * @property integer $bradId
     */

    public function __construct( Product $product,$config = [])
    {
        $this->brandId=$product->brand_id;
        $this->code=$product->code;
        $this->name=$product->name;
        $this->meta=new MetaForm($product->meta);
        $this->tags=new TagsForm($product);
        $this->values = array_map(function (Characteristic $characteristic) use ($product) {
            return new ValueForm($characteristic, $product->getValue($characteristic->id));
        }, Characteristic::find()->orderBy('sort')->all());
        $this->_product = $product;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['brandId', 'code', 'name'], 'required'],
            [['brandId'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
            [
                ['code'],
                'unique',
                'targetClass' => Product::class,
                'filter' => $this->_product ? ['<>', 'id', $this->_product->id] : null],
        ];
    }

    protected function internalForms(): array
    {
        return ['meta', 'tags', 'values'];
    }


}
