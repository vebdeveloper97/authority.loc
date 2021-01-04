<?php

use yii\db\Migration;

/**
 * Class m200723_074238_rename_employee_skills_table
 */
class m200723_074238_rename_employee_skills_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%employee_skills}}', 'hr_employee_skills');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('{{%hr_employee_skills}}', 'employee_skills');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200723_074238_rename_employee_skills_table cannot be reverted.\n";

        return false;
    }
    */
}
