<?php

use yii\db\Migration;
use shop\entities\shop\Categories;
use shop\entities\shop\Brand;
/**
 * Handles the creation of table `shop_products`.
 */
class m180919_053923_create_shop_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
