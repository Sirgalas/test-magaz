<?php

use yii\db\Migration;
/**
 * Class m180827_083735_add_column_background_from_service_table
 */
class m180827_083735_add_column_background_from_service_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180827_083735_add_column_background_from_service_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180827_083735_add_column_background_from_service_table cannot be reverted.\n";

        return false;
    }
    */
}
