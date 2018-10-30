<?php

namespace entities\shop\product;


use yii\db\ActiveRecord;

/**
 * @property integer $product_id;
 * @property integer $related_id;
 */
class RelatedAssignment extends  ActiveRecord
{
    public static function create($productId):self
    {

    }
}
