<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%evaluation_ru}}`.
 */
class m201104_125450_create_evaluation_ru_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%evaluation_ru}}', [
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
        $this->dropTable('{{%evaluation_ru}}');
    }
}
