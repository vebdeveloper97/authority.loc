<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message_ru}}`.
 */
class m201110_043535_add_top_column_to_message_ru_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%message_ru}}', 'top', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%message_ru}}', 'top');
    }
}
