<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reference}}`.
 */
class m201103_095116_create_reference_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reference}}', [
            'id' => $this->primaryKey(),
            'fullname' => $this->char(100)->notNull(),
            'address' => $this->char(100)->notNull(),
            'phone' => $this->char(20)->notNull(),
            'reference_message' => $this->text()->notNull(),
            'address' => $this->char(100)->notNull(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%reference}}');
    }
}
