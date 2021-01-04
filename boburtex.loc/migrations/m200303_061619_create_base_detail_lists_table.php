<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_detail_lists}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%parent}}`
 */
class m200303_061619_create_base_detail_lists_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_detail_lists}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'name' => $this->string(),
            'code' => $this->string(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'status' => $this->smallInteger()->defaultValue(1),
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
        $this->dropTable('{{%base_detail_lists}}');
    }
}
