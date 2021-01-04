<?php

use yii\db\Migration;

/**
 * Class m200814_051636_alter_start_date_and_end_date_columns_for_hr_department_responsible_person_table
 */
class m200814_051636_alter_start_date_and_end_date_columns_for_hr_department_responsible_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%hr_department_responsible_person}}', 'start_date', $this->date());
        $this->alterColumn('{{%hr_department_responsible_person}}', 'end_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%hr_department_responsible_person}}', 'start_date', $this->dateTime());
        $this->alterColumn('{{%hr_department_responsible_person}}', 'end_date', $this->dateTime());
    }
}
