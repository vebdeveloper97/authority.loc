<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_staff}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_position_type}}`
 */
class m200619_051739_add_position_type_id_column_to_hr_staff_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_staff}}', 'position_type_id', $this->integer());

        // creates index for column `position_type_id`
        $this->createIndex(
            '{{%idx-hr_staff-position_type_id}}',
            '{{%hr_staff}}',
            'position_type_id'
        );

        // add foreign key for table `{{%hr_position_type}}`
        $this->addForeignKey(
            '{{%fk-hr_staff-position_type_id}}',
            '{{%hr_staff}}',
            'position_type_id',
            '{{%hr_position_type}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_position_type}}`
        $this->dropForeignKey(
            '{{%fk-hr_staff-position_type_id}}',
            '{{%hr_staff}}'
        );

        // drops index for column `position_type_id`
        $this->dropIndex(
            '{{%idx-hr_staff-position_type_id}}',
            '{{%hr_staff}}'
        );

        $this->dropColumn('{{%hr_staff}}', 'position_type_id');
    }
}
