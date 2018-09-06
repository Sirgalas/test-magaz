<?php

namespace shop\repositories\shop;

use http\Exception\RuntimeException;
use shop\entities\shop\Categories;
use shop\repositories\NotFoundException;

class CategoryRepository
{
    public function get($id):Categories
    {
        if(!$category=Categories::findOne($id))
            throw new NotFoundException('Category is not found.');
        return $category;
    }

    public function save(Categories $category):void
    {
        if(!$category->save())
            throw new \RuntimeException($category->errors);
    }

    public function remove(Categories $categories):void
    {
        if(!$categories->delete())
            throw new \RuntimeException($categories->errors);
    }

}
