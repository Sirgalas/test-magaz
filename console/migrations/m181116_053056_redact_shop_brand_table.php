<?php

use yii\db\Migration;
use shop\entities\shop\Brand;
/**
 * Class m181116_053056_redact_shop_brand_table
 */
class m181116_053056_redact_shop_brand_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            '{{%idx-shop_brands-slug}}',
            Brand::tableName(),
            'slug',
            true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-shop_brands-slug}}', Brand::tableName());
    }

}
