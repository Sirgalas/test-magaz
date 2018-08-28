<?php

namespace shop\repositories\Shop;

use http\Exception\RuntimeException;
use shop\entities\shop\Tags;
use shop\repositories\NotFoundException;

class ShopRepository
{
    public function get($id): Tags
    {
        if (!$tag = Tags::findOne($id))
            throw new NotFoundException('Tag is not found.');
        return $tag;
    }

    public function save(Tags $tags):void
    {
        if(!$tags->save())
            throw new \RuntimeException('Saving error');
    }

    public function remove(Tags $tags):void
    {
        if(!$tags->delete())
            throw new \RuntimeException('Removing error.');
    }


}
