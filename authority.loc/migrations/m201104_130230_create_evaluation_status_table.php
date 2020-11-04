<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%evaluation_status}}`.
 */
class m201104_130230_create_evaluation_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%evaluation_status}}', [
            'id' => $this->primaryKey(),
            'ip_address' => $this->char(50)->notNull(),
            'evaluation_id' => $this->integer()->notNull(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%evaluation_status}}');
    }
}
