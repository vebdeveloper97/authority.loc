<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee}}`.
 */
class m200626_065908_add_inn_column_inps_column_to_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee}}', 'inn', $this->string());
        $this->addColumn('{{%hr_employee}}', 'inps', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_employee}}', 'inn');
        $this->dropColumn('{{%hr_employee}}', 'inps');
    }
}
