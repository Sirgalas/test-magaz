<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_characteristics`.
 */
class m180901_201808_create_shop_characteristics_table extends Migration
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
        $this->createTable('shop_characteristics', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull(),
            'type'=>$this->string(16)->notNull(),
            'required'=>$this->boolean()->notNull(),
            'default'=>$this->string()->notNull(),
            'variants_json'=>$this->json()->notNull(),
            'sort'=>$this->integer()->notNull(),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shop_characteristics}}');
    }
}
