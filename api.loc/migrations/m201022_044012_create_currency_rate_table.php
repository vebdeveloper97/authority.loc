<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency_rate}}`.
 */
class m201022_044012_create_currency_rate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency_rate}}', [
            'id' => $this->primaryKey(),
            'rate_name' => $this->string(20)->notNull(),
            'rate_usd' => $this->float()->notNull(),
            'status' => $this->integer()->defaultValue(1),
            'date' => $this->date(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency_rate}}');
    }
}
