<?php


namespace entities\shop\product;

use yii\db\ActiveRecord;

/**
 * Class CategoryAssignment
 * @package entities\shop\product
 * @property integer $product_id;
 * @property integer $category_id;
 */
class CategoryAssignment extends ActiveRecord
{
    public static function create(int $categoryId):self
    {
        $assignment = new static();
        $assignment->category_id=$categoryId;
        return $assignment;
    }

    public function isForCategory($id): bool
    {
        return $this->category_id == $id;
    }

    public static function tableName(): string
    {
        return '{{%shop_category_assignments}}';
    }
}
