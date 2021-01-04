<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%alter}}`.
 */
class m200616_195551_create_alter_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('ALTER TABLE `hr_employee_study` DROP FOREIGN KEY `fk-hr_employee_study-hr_employee_id`; ALTER TABLE `hr_employee_study` ADD CONSTRAINT `fk-hr_employee_study-hr_employee_id` FOREIGN KEY (`hr_employee_id`) REFERENCES `hr_employee`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
