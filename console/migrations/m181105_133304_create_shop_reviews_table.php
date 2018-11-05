<?php

use yii\db\Migration;
use shop\entities\shop\product\Product;
use shop\entities\user\User;
/**
 * Handles the creation of table `shop_reviews`.
 */
class m181105_133304_create_shop_reviews_table extends Migration
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

        $this->createTable('shop_reviews', [
            'id' => $this->primaryKey(),
            'created_at'=>$this->integer()->unsigned()->notNull(),
            'product_id'=>$this->integer()->notNull(),
            'user_id'=>$this->integer()->notNull(),
            'vote'=>$this->integer()->notNull(),
            'text'=>$this->text()->notNull(),
            'active'=>$this->boolean()->notNull()
        ],$tableOptions);

        $this->createIndex('{{%idx-shop_reviews-product_id}}', '{{%shop_reviews}}', 'product_id');
        $this->createIndex('{{%idx-shop_reviews-user_id}}', '{{%shop_reviews}}', 'user_id');

        $this->addForeignKey(
            '{{%fk-shop_reviews-product_id}}',
            '{{%shop_reviews}}',
            'product_id',
            Product::tableName(),
            current(Product::primaryKey()),
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk-shop_reviews-user_id}}',
            '{{%shop_reviews}}',
            'user_id',
            User::tableName(),
            current(User::primaryKey()),
            'CASCADE',
            'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('shop_reviews');
    }
}
