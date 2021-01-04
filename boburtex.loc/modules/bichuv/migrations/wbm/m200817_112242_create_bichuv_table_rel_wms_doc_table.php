<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_table_rel_wms_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_tables}}`
 * - `{{%wms_document}}`
 */
class m200817_112242_create_bichuv_table_rel_wms_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_table_rel_wms_doc}}', [
            'id' => $this->primaryKey(),
            'bichuv_table_id' => $this->integer(),
            'wms_doc_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11)
        ]);

        // creates index for column `bichuv_table_id`
        $this->createIndex(
            '{{%idx-bichuv_table_rel_wms_doc-bichuv_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}',
            'bichuv_table_id'
        );

        // add foreign key for table `{{%bichuv_tables}}`
        $this->addForeignKey(
            '{{%fk-bichuv_table_rel_wms_doc-bichuv_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}',
            'bichuv_table_id',
            '{{%bichuv_tables}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `wms_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_table_rel_wms_doc-wms_doc_id}}',
            '{{%bichuv_table_rel_wms_doc}}',
            'wms_doc_id'
        );

        // add foreign key for table `{{%wms_document}}`
        $this->addForeignKey(
            '{{%fk-bichuv_table_rel_wms_doc-wms_doc_id}}',
            '{{%bichuv_table_rel_wms_doc}}',
            'wms_doc_id',
            '{{%wms_document}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_tables}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_table_rel_wms_doc-bichuv_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}'
        );

        // drops index for column `bichuv_table_id`
        $this->dropIndex(
            '{{%idx-bichuv_table_rel_wms_doc-bichuv_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}'
        );

        // drops foreign key for table `{{%wms_document}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_table_rel_wms_doc-wms_doc_id}}',
            '{{%bichuv_table_rel_wms_doc}}'
        );

        // drops index for column `wms_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_table_rel_wms_doc-wms_doc_id}}',
            '{{%bichuv_table_rel_wms_doc}}'
        );

        $this->dropTable('{{%bichuv_table_rel_wms_doc}}');
    }
}
