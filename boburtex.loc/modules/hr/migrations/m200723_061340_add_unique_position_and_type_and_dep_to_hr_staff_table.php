<?php

use yii\db\Migration;

/**
 * Class m200723_061340_add_unique_position_and_type_and_dep_to_hr_staff_table
 */
class m200723_061340_add_unique_position_and_type_and_dep_to_hr_staff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_unique-hr_staff-position_id-department_id-position_type_id',
            'hr_staff',
            ['department_id', 'position_id', 'position_type_id'],
            true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_unique-hr_staff-position_id-department_id-position_type_id',
            'hr_staff');
    }
}
