<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}} and {{user_roles}}`.
 */
class m191007_081043_add_some_column_to_users_table_and_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%users}}', 'code', $this->string(30));
        $this->addColumn('{{%user_roles}}', 'code', $this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%users}}', 'code');
        $this->dropColumn('{{%user_roles}}', 'code');
    }
}
