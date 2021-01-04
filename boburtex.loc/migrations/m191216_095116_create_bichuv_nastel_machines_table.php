<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_machines}}`.
 */
class m191216_095116_create_bichuv_nastel_machines_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_machines}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'add_info' => $this->text(),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->bigInteger(20),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bichuv_nastel_machines}}');
    }
}
