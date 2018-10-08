<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_category_assignments`.
 */
class m181008_052703_create_shop_category_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_category_assignments', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('shop_category_assignments');
    }
}
