<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 06.11.18
 * Time: 8:28
 */

namespace shop\services\manage\shop;


use repositories\shop\ProductRepository;
use shop\forms\manage\shop\product\ReviewEditForm;

class ReviewManageService
{
    private $products;

    public function __construct(ProductRepository $products)
    {
        $this->products=$products;
    }

    public function edit($id, $reviewId, ReviewEditForm $form):void
    {
        $product=$this->products->get($id);
        $product->editReview(
            $reviewId,
            $form->vote,
            $form->text
        );
        $this->products->get($id);
    }

    public function activete($id, $reviewId):void
    {
        $product=$this->products->get($id);
        $product->activateReview($reviewId);
        $this->products->save($product);
    }

    public function draft($id, $reviewId):void
    {
        $product = $this->products->get($id);
        $product->draftReview($reviewId);
        $this->products->save($product);
    }

    public function remove($id,$reviewId):void
    {
        $product = $this->products->get($id);
        $product->removeReview($reviewId);
        $this->products->save($product);
    }
}
