<?php


namespace shop\services\manage;

use forms\manage\shop\CategoryForm;
use shop\entities\Meta;
use shop\entities\shop\Categories;
use shop\repositories\shop\CategoryRepository;

class CategoryManageService
{
   private $repository;

   public function __construct(CategoryRepository $repository)
   {
       $this->repository=$repository;
   }

   public function create(CategoryForm $form):Categories
   {
       $parent=$this->repository->get($form->parentId);
       $category= Categories::create(
           $form,
           new Meta(
               $form->meta->title,
               $form->meta->description,
               $form->meta->keywords
           )
       );
       $category->appendTo($parent);
       $this->repository->save($category);
       return $category;
   }

   public function edit($id,CategoryForm $form):void
   {
        $category=$this->repository->get($id);
        $this->assertIsNotRoot($category);
        $category->edit(
            $form,
            new Meta(
                $form->meta->title,
                $form->meta->description,
                $form->meta->keywords)
        );
        if($form->parentId!== $category->parentId)
        {
            $parent=$this->repository->get($category->parentId);
            $category->appendTo($parent);
        }
        $this->repository->save($category);
   }

   public function remove($id):void
   {
       $category=$this->repository->get($id);
       $this->assertIsNotRoot($category);
       $this->repository->save($category);
   }

   private function assertIsNotRoot(Categories $category ):void
   {
       if($category)
           throw new \DomainException('Unable manage root category');
   }
}
