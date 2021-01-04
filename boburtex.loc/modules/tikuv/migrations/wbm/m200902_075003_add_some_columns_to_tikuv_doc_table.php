<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 * - `{{%hr_employee}}`
 * - `{{%hr_employee}}`
 */
class m200902_075003_add_some_columns_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc}}', 'from_hr_department', $this->integer());
        $this->addColumn('{{%tikuv_doc}}', 'to_hr_department', $this->integer());
        $this->addColumn('{{%tikuv_doc}}', 'from_hr_employee', $this->integer());
        $this->addColumn('{{%tikuv_doc}}', 'to_hr_employee', $this->integer());

        // creates index for column `from_hr_department`
        $this->createIndex(
            '{{%idx-tikuv_doc-from_hr_department}}',
            '{{%tikuv_doc}}',
            'from_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-from_hr_department}}',
            '{{%tikuv_doc}}',
            'from_hr_department',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_hr_department`
        $this->createIndex(
            '{{%idx-tikuv_doc-to_hr_department}}',
            '{{%tikuv_doc}}',
            'to_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-to_hr_department}}',
            '{{%tikuv_doc}}',
            'to_hr_department',
            '{{%hr_departments}}',
            'id',
            'CASCADE'
        );

        // creates index for column `from_hr_employee`
        $this->createIndex(
            '{{%idx-tikuv_doc-from_hr_employee}}',
            '{{%tikuv_doc}}',
            'from_hr_employee'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-from_hr_employee}}',
            '{{%tikuv_doc}}',
            'from_hr_employee',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );

        // creates index for column `to_hr_employee`
        $this->createIndex(
            '{{%idx-tikuv_doc-to_hr_employee}}',
            '{{%tikuv_doc}}',
            'to_hr_employee'
        );

        // add foreign key for table `{{%hr_employee}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-to_hr_employee}}',
            '{{%tikuv_doc}}',
            'to_hr_employee',
            '{{%hr_employee}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-from_hr_department}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `from_hr_department`
        $this->dropIndex(
            '{{%idx-tikuv_doc-from_hr_department}}',
            '{{%tikuv_doc}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-to_hr_department}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `to_hr_department`
        $this->dropIndex(
            '{{%idx-tikuv_doc-to_hr_department}}',
            '{{%tikuv_doc}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-from_hr_employee}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `from_hr_employee`
        $this->dropIndex(
            '{{%idx-tikuv_doc-from_hr_employee}}',
            '{{%tikuv_doc}}'
        );

        // drops foreign key for table `{{%hr_employee}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-to_hr_employee}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `to_hr_employee`
        $this->dropIndex(
            '{{%idx-tikuv_doc-to_hr_employee}}',
            '{{%tikuv_doc}}'
        );

        $this->dropColumn('{{%tikuv_doc}}', 'from_hr_department');
        $this->dropColumn('{{%tikuv_doc}}', 'to_hr_department');
        $this->dropColumn('{{%tikuv_doc}}', 'from_hr_employee');
        $this->dropColumn('{{%tikuv_doc}}', 'to_hr_employee');
    }
}
