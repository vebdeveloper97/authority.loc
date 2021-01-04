<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_employee}}`.
 */
class m200723_103158_add_foreignkey_level_column_to_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-hr_employee_study-degree', '{{%hr_employee_study}}', 'degree');

        $this->addForeignKey(
            'fk-hr_employee_study-degree',
            '{{%hr_employee_study}}',
            'degree',
            '{{%hr_study_degree}}',
            'id'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-hr_employee_study-degree',
            '{{%hr_employee_study}}'
        );

        $this->dropIndex('idx-hr_employee_study-degree', '{{%hr_employee_study}}');

    }
}
