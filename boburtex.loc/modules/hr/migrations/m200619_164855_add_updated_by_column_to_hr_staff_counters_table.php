<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_staff_counters}}`.
 */
class m200619_164855_add_updated_by_column_to_hr_staff_counters_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_staff_counters}}', 'updated_by', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_staff_counters}}', 'updated_by');
    }
}
