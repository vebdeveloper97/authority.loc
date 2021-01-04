<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wms_department_area}}`
 * - `{{%wms_department_area}}`
 */
class m200630_090954_add_from_area_and_to_area_columns_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'from_area', $this->integer());
        $this->addColumn('{{%bichuv_doc}}', 'to_area', $this->integer());

        // creates index for column `from_area`
        $this->createIndex(
            '{{%idx-bichuv_doc-from_area}}',
            '{{%bichuv_doc}}',
            'from_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-from_area}}',
            '{{%bichuv_doc}}',
            'from_area',
            '{{%wms_department_area}}',
            'id'
        );

        // creates index for column `to_area`
        $this->createIndex(
            '{{%idx-bichuv_doc-to_area}}',
            '{{%bichuv_doc}}',
            'to_area'
        );

        // add foreign key for table `{{%wms_department_area}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-to_area}}',
            '{{%bichuv_doc}}',
            'to_area',
            '{{%wms_department_area}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-from_area}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `from_area`
        $this->dropIndex(
            '{{%idx-bichuv_doc-from_area}}',
            '{{%bichuv_doc}}'
        );

        // drops foreign key for table `{{%wms_department_area}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-to_area}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `to_area`
        $this->dropIndex(
            '{{%idx-bichuv_doc-to_area}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'from_area');
        $this->dropColumn('{{%bichuv_doc}}', 'to_area');
    }
}
