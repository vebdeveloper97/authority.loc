<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%templates}}`.
 */
class m200514_111506_create_templates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%templates}}', [
            'id' => $this->bigInteger(),
            'name' => $this->string(255),
            'add_info' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%templates}}');
    }
}
