<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_list}}`.
 */
class m191028_171627_add_some_column_to_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_list}}', 'baski', $this->boolean()->defaultValue(1));
        $this->addColumn('{{%models_list}}', 'prints', $this->boolean()->defaultValue(1));
        $this->addColumn('{{%models_list}}', 'stone', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_list}}', 'baski');
        $this->dropColumn('{{%models_list}}', 'prints');
        $this->dropColumn('{{%models_list}}', 'stone');
    }
}
