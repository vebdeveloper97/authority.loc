<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%evaluation_uz}}`.
 */
class m201104_125444_create_evaluation_uz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%evaluation_uz}}', [
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
        $this->dropTable('{{%evaluation_uz}}');
    }
}
