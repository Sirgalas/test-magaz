<?php

use yii\db\Migration;
use shop\entities\user\User;
/**
 * Class m180725_202630_change_users_field_requirements
 */
class m180725_202630_change_users_field_requirements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn(User::tableName(),'username',$this->string());
        $this->alterColumn(User::tableName(),'password_hash',$this->string());
        $this->alterColumn(User::tableName(),'email',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn(User::tableName(),'username',$this->string()->notNull());
        $this->alterColumn(User::tableName(),'password_hash',$this->string()->notNull());
        $this->alterColumn(User::tableName(),'email',$this->string()->notNull());
    }


}
