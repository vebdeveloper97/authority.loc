<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_rolls}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 */
class m200818_070517_add_nastel_employee_id_column_to_bichuv_given_rolls_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_given_rolls}}', 'nastel_employee_id', $this->integer());

        // creates index for column `nastel_employee_id`
        $this->createIndex(
            '{{%idx-bichuv_given_rolls-nastel_employee_id}}',
            '{{%bichuv_given_rolls}}',
            'nastel_employee_id'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_rolls-nastel_employee_id}}',
            '{{%bichuv_given_rolls}}',
            'nastel_employee_id',
            '{{%hr_employee}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_rolls-nastel_employee_id}}',
            '{{%bichuv_given_rolls}}'
        );

        // drops index for column `nastel_employee_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_rolls-nastel_employee_id}}',
            '{{%bichuv_given_rolls}}'
        );

        $this->dropColumn('{{%bichuv_given_rolls}}', 'nastel_employee_id');
    }
}
