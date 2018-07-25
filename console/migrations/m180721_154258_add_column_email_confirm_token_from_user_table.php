<?php

use yii\db\Migration;
use shop\entities\User\User;
/**
 * Class m180721_154258_add_column_email_confirm_token_from_user_table
 */
class m180721_154258_add_column_email_confirm_token_from_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(User::tableName(),'email_confirm_signup',$this->string()->unique()->after('email'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(User::tableName(),'email_confirm_signup');
    }

}
