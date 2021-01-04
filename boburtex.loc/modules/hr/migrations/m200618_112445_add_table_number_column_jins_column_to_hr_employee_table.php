<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee}}`.
 */
class m200618_112445_add_table_number_column_jins_column_to_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee}}', 'table_number', $this->integer());
        $this->addColumn('{{%hr_employee}}', 'jins', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_employee}}', 'table_number');
        $this->dropColumn('{{%hr_employee}}', 'jins');
    }
}
