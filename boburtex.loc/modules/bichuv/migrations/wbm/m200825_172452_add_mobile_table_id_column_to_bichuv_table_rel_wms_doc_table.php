<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_table_rel_wms_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_tables}}`
 */
class m200825_172452_add_mobile_table_id_column_to_bichuv_table_rel_wms_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_table_rel_wms_doc}}', 'mobile_table_id', $this->integer());

        // creates index for column `mobile_table_id`
        $this->createIndex(
            '{{%idx-bichuv_table_rel_wms_doc-mobile_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}',
            'mobile_table_id'
        );

        // add foreign key for table `{{%mobile_tables}}`
        $this->addForeignKey(
            '{{%fk-bichuv_table_rel_wms_doc-mobile_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}',
            'mobile_table_id',
            '{{%mobile_tables}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_tables}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_table_rel_wms_doc-mobile_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}'
        );

        // drops index for column `mobile_table_id`
        $this->dropIndex(
            '{{%idx-bichuv_table_rel_wms_doc-mobile_table_id}}',
            '{{%bichuv_table_rel_wms_doc}}'
        );

        $this->dropColumn('{{%bichuv_table_rel_wms_doc}}', 'mobile_table_id');
    }
}
