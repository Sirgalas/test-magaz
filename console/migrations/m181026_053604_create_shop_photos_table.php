<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_photos`.
 */
class m181026_053604_create_shop_photos_table extends Migration
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
        $this->createTable('shop_photos', [
            'id' => $this->primaryKey(),
            'product_id'=> $this->integer()->notNull(),
            'file'=>$this->string()->notNull(),
            'sort'=>$this->string()->notNull()
        ],
            $tableOptions);

        $this->createIndex('{{%idx-shop_photos-product_id}}', '{{%shop_photos}}', 'product_id');
        $this->addForeignKey(
            '{{%fk-shop_photos-product_id}}',
            '{{%shop_photos}}',
            'product_id',
            '{{%shop_products}}',
            'id',
            'CASCADE',
            'RESTRICT');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-shop_photos-product_id}}', '{{%shop_photos}}');

        $this->dropForeignKey(
            '{{%fk-shop_photos-product_id}}',
            '{{%shop_photos}}');

        $this->dropTable('shop_photos');

    }
}
