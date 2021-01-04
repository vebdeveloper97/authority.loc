<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%unit}}`.
 */
class m191014_150520_add_code_column_to_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('unit','code', $this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('unit','code');
    }
}
