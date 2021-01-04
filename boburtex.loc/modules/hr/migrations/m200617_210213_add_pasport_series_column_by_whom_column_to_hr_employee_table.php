<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee}}`.
 */
class m200617_210213_add_pasport_series_column_by_whom_column_to_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee}}', 'pasport_series', $this->char(10)->unique());
        $this->addColumn('{{%hr_employee}}', 'by_whom', $this->char(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_employee}}', 'pasport_series');
        $this->dropColumn('{{%hr_employee}}', 'by_whom');
    }
}
