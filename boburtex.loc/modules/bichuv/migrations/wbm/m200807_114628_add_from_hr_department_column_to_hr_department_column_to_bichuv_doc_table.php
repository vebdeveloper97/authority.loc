<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%hr_departments}}`
 * - `{{%hr_departments}}`
 */
class m200807_114628_add_from_hr_department_column_to_hr_department_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'from_hr_department', $this->integer());
        $this->addColumn('{{%bichuv_doc}}', 'to_hr_department', $this->integer());

        // creates index for column `from_hr_department`
        $this->createIndex(
            '{{%idx-bichuv_doc-from_hr_department}}',
            '{{%bichuv_doc}}',
            'from_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-from_hr_department}}',
            '{{%bichuv_doc}}',
            'from_hr_department',
            '{{%hr_departments}}',
            'id'
        );

        // creates index for column `to_hr_department`
        $this->createIndex(
            '{{%idx-bichuv_doc-to_hr_department}}',
            '{{%bichuv_doc}}',
            'to_hr_department'
        );

        // add foreign key for table `{{%hr_departments}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-to_hr_department}}',
            '{{%bichuv_doc}}',
            'to_hr_department',
            '{{%hr_departments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-from_hr_department}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `from_hr_department`
        $this->dropIndex(
            '{{%idx-bichuv_doc-from_hr_department}}',
            '{{%bichuv_doc}}'
        );

        // drops foreign key for table `{{%hr_departments}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-to_hr_department}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `to_hr_department`
        $this->dropIndex(
            '{{%idx-bichuv_doc-to_hr_department}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'from_hr_department');
        $this->dropColumn('{{%bichuv_doc}}', 'to_hr_department');
    }
}
