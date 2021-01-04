<?php

use yii\db\Migration;

/**
 * Class m200723_102506_alter_level_column_for_hr_employee_table
 */
class m200723_102506_add_degree_column_to_hr_employee_study_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_employee_study}}', 'degree', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_employee_study}}', 'degree');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200723_102506_alter_level_column_for_hr_employee_table cannot be reverted.\n";

        return false;
    }
    */
}
