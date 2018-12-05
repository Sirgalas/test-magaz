<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20.09.18
 * Time: 8:32
 */

namespace repositories\shop;

use shop\entities\shop\product\Product;
use shop\repositories\NotFoundException;

class ProductRepository
{
    public function get($id):Product
    {
        if(!$product=Product::findOne($id))
            throw new NotFoundException('Product not find');
        return $product;
    }

    public function save(Product $product):Product
    {
        if(!$product->save())
            throw new \RuntimeException(var_dump($product->errors));
        return  $product;
    }

    public function remove(int $id):void
    {
       $product=$this->get($id);
       $product->delete();
    }

    public function existsByBrand($id):bool
    {
        return Product::find()->andWhere(['barnd_id'=>$id])->exists();
    }

    public function existByMainCategory($id):bool
    {
        return Product::find()->andWhere(['category_id'=>$id])->exists();
    }
}
