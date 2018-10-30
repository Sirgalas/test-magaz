<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.09.18
 * Time: 8:39
 */

namespace services\manage\shop;

use forms\manage\shop\product\CategoriesForm;
use shop\entities\Meta;
use forms\manage\shop\product\ProductCreateForm;
use repositories\shop\ProductRepository;
use shop\entities\shop\product\Product;
use shop\forms\manage\shop\product\PhotosForm;
use shop\repositories\BrandRepository;
use shop\repositories\shop\CategoryRepository;

class ProductManageService
{
    private $products;
    private $brands;
    private $categories;

    public function __construct(
        ProductRepository $products,
        BrandRepository $brands,
        CategoryRepository $categories
    )
    {
        $this->products=$products;
        $this->brands=$brands;
        $this->categories=$categories;
    }

    public function create(ProductCreateForm $form)
    {
        $brand=$this->brands->get($form->brandId);
        $category=$this->categories->get($form->categories->main);

        $product =Product::create(
            $form,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords)
        );

        $product->setPrice($form->price->new, $form->price->old);

        foreach($form->categories->outher as $outherId){
              $category=$this->categories->get($outherId);
              $product->assignCategory($category->id);
        }

        foreach ($form->values as $value) {
            $product->setValue($value->id, $value->value);
        }

        foreach ($form->photos->files as $file){
            $product->addPhoto($file);
        }

        return  $this->products->save($product);;
    }


    public function changeCategories($id, CategoriesForm $form):void
    {
        $product=$this->products->get($id);
        $category=$this->categories->get($id);
        $product->changeMainCategory($category->id);
        $product->revokeCategories();
        foreach ($form->outher as $outherId){
            $category=$this->categories->get($outherId);
            $product->assignCategory($category->id);
        }
        $this->products->save($product);
    }

    public function addPhotos($id, PhotosForm $form):void
    {
        $product=$this->products->get($id);
        foreach ($form->files as $file){
            $product->addPhoto($file);
        }
    }

    public function movePhotosUp($id, $photoId):void
    {
        $product=$this->products->get($id);
        $product->movePhotoUp($photoId);
        $product->save();
    }

    public function movePhotoDown($id, $photoId):void
    {
        $product=$this->products->get($id);
        $product->movePhotoDown($photoId);
        $product->save();
    }

    public function removePhoto($id,$photoId):void {
        $product=$this->products->get($id);
        $product->removePhoto($photoId);
        $product->save();
    }

    public function remove(int $id):void
    {
        $this->products->remove($id);
    }

}
