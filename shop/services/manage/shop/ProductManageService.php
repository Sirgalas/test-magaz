<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.09.18
 * Time: 8:39
 */

namespace services\manage\shop;

use shop\entities\Meta;
use forms\manage\shop\product\ProductCreateForm;
use repositories\shop\ProductRepository;
use shop\entities\shop\product\Product;
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
        $category=$this->categories->get($form->category->main);

        $product =Product::create(
            $form,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords)
        );

        $product->setPrice($form->price->new, $form->price->old);

        foreach($form->categories->outher as $outherId){
              $category=$form->categories->outher($outherId);
              $product->assignCategory($category->id);
        }

        return  $this->products->save($product);;
    }

    public function remove(int $id):void
    {
        $this->products->remove($id);
    }

}
