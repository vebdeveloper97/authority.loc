<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee}}`.
 */
class m200626_071506_add_gender_column_to_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee}}', 'gender', $this->tinyInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_employee}}', 'gender');
    }
}
