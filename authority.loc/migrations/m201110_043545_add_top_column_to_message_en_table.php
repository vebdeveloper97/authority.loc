<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message_en}}`.
 */
class m201110_043545_add_top_column_to_message_en_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%message_en}}', 'top', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%message_en}}', 'top');
    }
}
