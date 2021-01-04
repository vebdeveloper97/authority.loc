<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_lists}}`.
 */
class m200818_110342_create_bichuv_nastel_lists_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_lists}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->unique(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%bichuv_nastel_lists}}');
    }
}
