<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m200118_072713_add_some_columns_to_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%user_roles}}', ['id' => 10, 'role_name' => "To'quv mato sklad", 'code' => "TOQUV_MATO_SKLAD", 'department' => 'toquv'],true);
        $this->addColumn('{{%users}}', 'status', $this->smallInteger()->defaultValue(1));
        $this->addColumn('{{%users}}','deleted_time', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user_roles}}', ['id' => 10, 'role_name' => "To'quv mato sklad", 'code' => "TOQUV_MATO_SKLAD", 'department' => 'toquv']);
        $this->dropColumn('{{%users}}', 'status');
        $this->dropColumn('{{%users}}','deleted_time');
    }
}
