<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_addition_task_to_employees}}`.
 */
class m200723_130321_add_reg_date_column_to_hr_addition_task_to_employees_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_addition_task_to_employees}}', 'reg_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_addition_task_to_employees}}', 'reg_date');
    }
}
