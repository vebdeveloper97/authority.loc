<?php

use yii\db\Migration;

/**
 * Class m191007_114021_upsert_data_to_user_roles_table
 */
class m191007_114021_upsert_data_to_user_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%user_roles}}', ['role_name' => "To'quv master", 'code' => "TOQUV_MASTER"],true);
        $this->upsert('{{%user_roles}}', ['role_name' => "To'quv kalite", 'code' => "TOQUV_KALITE"],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user_roles}}', ['role_name' => "To'quv master", 'code' => "TOQUV_MASTER"]);
        $this->delete('{{%user_roles}}', ['role_name' => "To'quv kalite", 'code' => "TOQUV_KALITE"]);
    }
}
