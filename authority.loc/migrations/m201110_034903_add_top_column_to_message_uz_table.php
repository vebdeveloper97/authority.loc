<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%message_uz}}`.
 */
class m201110_034903_add_top_column_to_message_uz_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%message_uz}}', 'top', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%message_uz}}', 'top');
    }
}
