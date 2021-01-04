<?php

use yii\db\Migration;

/**
 * Class m200723_075518_alter_pasport_series_for_hr_employee_table
 */
class m200723_075518_alter_pasport_series_for_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('hr_employee', 'pasport_series', $this->char(25));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('hr_employee', 'pasport_series', $this->char(10));

    }
}
