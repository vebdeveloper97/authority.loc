<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process}}`.
 */
class m200824_103545_add_process_order_column_to_mobile_process_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process}}', 'process_order', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mobile_process}}', 'process_order');
    }
}
