<?php
namespace shop\forms\manage\shop;

use shop\entities\Shop\Brand;
use shop\forms\manage\MetaForm;
use shop\validator\SlugValidator;
use yii\helpers\ArrayHelper;
use shop\forms\CompositeForm;
/**
 * @property MetaForm $meta;
 */
class BrandForm extends CompositeForm
{
    public $name;
    public $slug;
    private $_meta;
    private $_brand;

    public function __construct(Brand $brand = null, $config = [])
    {
        if ($brand) {
            $this->name = $brand->name;
            $this->slug = $brand->slug;
            $this->_meta = new MetaForm($brand->meta);
            $this->_brand = $brand;
        }else{
            $this->_meta = new MetaForm();
        }
        parent::__construct($config);
    }


    public function rules(): array
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            ['slug', SlugValidator::class ],
            [
                ['name', 'slug'],
                'unique',
                'targetClass' => Brand::class,
                'filter' => $this->_brand ? ['<>', 'id', $this->_brand->id] : null
            ]
        ];
    }

    public function internalForms(): array
    {
        return ['meta'];
    }
}
