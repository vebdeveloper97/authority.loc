<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%color_panton_type}}`.
 */
class m190904_121223_create_color_panton_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%color_panton_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%color_panton_type}}');
    }
}
