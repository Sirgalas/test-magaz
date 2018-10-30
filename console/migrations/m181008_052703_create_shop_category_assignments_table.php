<?php

use yii\db\Migration;
use shop\entities\shop\product\Product;
use shop\entities\shop\Categories;
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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%shop_category_assignments}}', [
            'product_id' => $this->integer()->notNull(),
            'category_id' =>$this->integer()->notNull()
        ],$tableOptions);

        $this->addPrimaryKey(
            '{{%pk-shop_category_assignments}}',
            '{{%shop_category_assignments}}',
            ['product_id', 'category_id']);

        $this->createIndex(
            '{{%idx-shop_category_assignments-product_id}}',
            '{{%shop_category_assignments}}',
            'product_id');

        $this->createIndex(
            '{{%idx-shop_category_assignments-category_id}}',
            '{{%shop_category_assignments}}',
            'category_id');

        $this->addForeignKey(
            '{{%fk-shop_category_assignments-product_id}}',
            '{{%shop_category_assignments}}',
            'product_id',
            Product::tableName(),
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
             '{{%fk-shop_category_assignments-category_id}}',
            '{{%shop_category_assignments}}',
            'category_id',
             Categories::tableName(),
             'id',
            'CASCADE',
             'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            '{{%idx-shop_category_assignments-product_id}}',
            '{{%shop_category_assignments}}'
        );

        $this->dropIndex(
            '{{%idx-shop_category_assignments-category_id}}',
            '{{%shop_category_assignments}}'
        );

        $this->dropForeignKey(
            '{{%fk-shop_category_assignments-product_id}}',
            '{{%shop_category_assignments}}'

        );

        $this->dropForeignKey(
            '{{%fk-shop_category_assignments-product_id}}',
            '{{%shop_category_assignments}}'
        );
        $this->dropTable('shop_category_assignments');
    }
}
