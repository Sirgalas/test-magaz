<?php


namespace shop\services\manage\shop;

use repositories\shop\ProductRepository;
use shop\forms\manage\shop\CategoryForm;
use shop\entities\Meta;
use shop\entities\shop\Categories;
use shop\repositories\shop\CategoryRepository;

class CategoryManageService
{
   private $repository;
   private $product;

   public function __construct(CategoryRepository $repository,ProductRepository $product)
   {
       $this->repository=$repository;
       $this->product=$product;
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
       if($this->product->existByMainCategory($category->id)){
           throw new \DomainException('Unable to remove category with products.');
       }
       $this->repository->save($category);
   }

   public function moveUp($id):void
   {
       $category=$this->repository->get($id);
       $this->assertIsNotRoot($category);
       if($prev=$category->prev){
           $category->insertBefore($prev);
       }
       $this->repository->save($category);
   }

   public function moveDown($id):void
   {
       $category=$this->repository->get($id);
       $this->assertIsNotRoot($category);
       if($prev=$category->prev){
           $category->insertAfter($prev);
       }
       $this->repository->save($category);
   }

   private function assertIsNotRoot(Categories $category ):void
   {
       if($category)
           throw new \DomainException('Unable manage root category');
   }
}
