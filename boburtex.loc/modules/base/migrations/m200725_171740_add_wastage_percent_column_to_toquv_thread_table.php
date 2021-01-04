<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_thread}}`.
 */
class m200725_171740_add_wastage_percent_column_to_toquv_thread_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_thread}}', 'wastage_percent', $this->decimal(3,2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_thread}}', 'wastage_percent');
    }
}
