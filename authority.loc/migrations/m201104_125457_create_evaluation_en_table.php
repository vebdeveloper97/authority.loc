<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%evaluation_en}}`.
 */
class m201104_125457_create_evaluation_en_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%evaluation_en}}', [
            'id' => $this->primaryKey(),
            'name' => $this->char(50)->notNull(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%evaluation_en}}');
    }
}
