<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%finance_expense_list}}`.
 */
class m200424_112734_create_finance_expense_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%finance_expense_list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
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
        $this->dropTable('{{%finance_expense_list}}');
    }
}
