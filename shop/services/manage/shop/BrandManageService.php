<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.08.18
 * Time: 23:00
 */

namespace shop\services\manage\shop;

use shop\entities\Meta;
use shop\entities\shop\Brand;
use shop\forms\manage\shop\BrandForm;
use shop\repositories\BrandRepository;
use yii\helpers\Inflector;

class BrandManageService
{
    private $brand;

    public function __construct(BrandRepository $brand)
    {
        $this->brand=$brand;
    }

    public function create(BrandForm $form) : Brand
    {
          $brand=Brand::create(
              $form->name,
              $form->slug?:Inflector::slug($form->name),
              new Meta(
                  $form->meta->title,
                  $form->meta->description,
                  $form->meta->keywords
              )
          );
          $this->brand->save($brand);
          return $brand;
    }

    public function edit($id, BrandForm $form): void
    {
         $brand=$this->brand->get($id);
         $brand->edit(
             $form->name,
             $form->slug?:Inflector::slug($form->name),
             new Meta(
                 $form->meta->title,
                 $form->meta->description,
                 $form->meta->keywords
             )
         );
         $this->brand->save($brand);
    }

    public function remove($id) : void
    {
        $brand=$this->brand->get($id);
        $this->brand->remove($brand);
    }

}
