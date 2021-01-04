<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%size_name}}`.
 */
class m191014_150520_add_code_column_to_sort_name_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sort_name','code', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sort_name','code');
    }
}
