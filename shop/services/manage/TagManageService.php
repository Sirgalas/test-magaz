<?php
namespace shop\services\manage\shop;

use shop\entities\shop\Tags;
use shop\forms\manage\shop\TagForm;
use shop\repositories\shop\TagRepository;
use yii\helpers\Inflector;

class TagManageService
{
    private $tags;
    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }
    public function create(TagForm $form): Tags
    {
        $tag = Tags::create(
            $form->name,
            $form->slug ?: Inflector::slug($form->name)
        );
        $this->tags->save($tag);
        return $tag;
    }
    public function edit($id, TagForm $form): void
    {
        $tag = $this->tags->get($id);
        $tag->edit(
            $form->name,
            $form->slug ?: Inflector::slug($form->name)
        );
        $this->tags->save($tag);
    }
    public function remove($id): void
    {
        $tag = $this->tags->get($id);
        $this->tags->remove($tag);
    }
}
