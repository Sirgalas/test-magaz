<?php
namespace shop\repositories\Shop;
use shop\entities\Shop\Tags;
use shop\repositories\NotFoundException;
class TagRepository
{
    public function get($id): Tags
    {
        if (!$tag = Tags::findOne($id)) {
            throw new NotFoundException('Tag is not found.');
        }
        return $tag;
    }
    public function save(Tags $tag): void
    {
        if (!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }
    public function remove(Tags $tag): void
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}
