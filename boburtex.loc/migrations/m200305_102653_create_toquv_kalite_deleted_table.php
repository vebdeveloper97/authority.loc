<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_kalite_deleted}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_instructions}}`
 */
class m200305_102653_create_toquv_kalite_deleted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_kalite_deleted}}', [
            'id' => $this->primaryKey(),
            'toquv_instructions_id' => $this->integer(),
            'toquv_rm_order_id' => $this->integer(),
            'toquv_makine_id' => $this->integer(),
            'user_id' => $this->integer(),
            'quantity' => $this->decimal(20.2),
            'sort_name_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'order' => $this->integer(),
            'code' => $this->string(60),
            'smena' => $this->string(3),
            'count' => $this->double()->defaultValue(0),
            'roll' => $this->double()->defaultValue(0),
            'user_kalite_id' => $this->integer(),
            'send_date' => $this->dateTime(),
            'send_user_id' => $this->integer(),
            'add_info' => $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%toquv_kalite_deleted}}');
    }
}
