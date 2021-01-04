<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%size_type}}`.
 */
class m191014_150520_add_code_column_to_size_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('size_type','code', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('size_type','code');
    }
}
