<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_orders}}`.
 */
class m191025_153950_add_type_column_to_toquv_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_orders}}', 'type', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_orders}}', 'type');
    }
}
