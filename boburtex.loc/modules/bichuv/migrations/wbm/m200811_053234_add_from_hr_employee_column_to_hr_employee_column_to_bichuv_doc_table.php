<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_employee}}`
 * - `{{%hr_employee}}`
 */
class m200811_053234_add_from_hr_employee_column_to_hr_employee_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'from_hr_employee', $this->integer());
        $this->addColumn('{{%bichuv_doc}}', 'to_hr_employee', $this->integer());

        // creates index for column `from_hr_employee`
        $this->createIndex(
            '{{%idx-bichuv_doc-from_hr_employee}}',
            '{{%bichuv_doc}}',
            'from_hr_employee'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-from_hr_employee}}',
            '{{%bichuv_doc}}',
            'from_hr_employee',
            '{{%hr_employee}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `to_hr_employee`
        $this->createIndex(
            '{{%idx-bichuv_doc-to_hr_employee}}',
            '{{%bichuv_doc}}',
            'to_hr_employee'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-to_hr_employee}}',
            '{{%bichuv_doc}}',
            'to_hr_employee',
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
            '{{%fk-bichuv_doc-from_hr_employee}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `from_hr_employee`
        $this->dropIndex(
            '{{%idx-bichuv_doc-from_hr_employee}}',
            '{{%bichuv_doc}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-to_hr_employee}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `to_hr_employee`
        $this->dropIndex(
            '{{%idx-bichuv_doc-to_hr_employee}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'from_hr_employee');
        $this->dropColumn('{{%bichuv_doc}}', 'to_hr_employee');
    }
}
