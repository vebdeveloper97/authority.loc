<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_pattern_part}}`.
 */
class m200417_164700_create_base_pattern_part_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_pattern_part}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull(),
            'token'       => $this->string(),
            'type'       => $this->smallInteger()->defaultValue(1),
            'status'     => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%base_pattern_part}}');
    }
}
