<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shop_categories`.
 */
class m180829_053902_create_shop_categories_table extends Migration
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
        $this->createTable('{{%shop_categories}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull(),
            'slug'=>$this->string()->notNull(),
            'title'=>$this->string(),
            'description'=>$this->text(),
            'meta_json'=>$this->json()->notNull(),
            'lft'=>$this->integer()->notNull(),
            'rgt'=>$this->integer()->notNull(),
            'depth'=>$this->integer()
        ],$tableOptions);

        $this->createIndex('{{%idx-category_shop_slug}}','{{%shop_categories}}','slug',true);

        $this->insert('{{%shop_categories}}',[
            'id' => 1,
            'name' => '',
            'slug' => 'root',
            'title' => null,
            'description' => null,
            'meta_json' => '{}',
            'lft' => 1,
            'rgt' => 2,
            'depth' => 0,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('shop_categories');
    }
}
