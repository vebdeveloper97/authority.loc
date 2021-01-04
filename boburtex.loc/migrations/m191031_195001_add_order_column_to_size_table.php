<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%size}}`.
 */
class m191031_195001_add_order_column_to_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%size}}', 'order', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%size}}', 'order');
    }
}
