<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_nastel_lists}}`
 */
class m200820_060950_add_bichuv_nastel_list_id_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'bichuv_nastel_list_id', $this->integer());

        // creates index for column `bichuv_nastel_list_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-bichuv_nastel_list_id}}',
            '{{%bichuv_doc}}',
            'bichuv_nastel_list_id'
        );

        // add foreign key for table `{{%bichuv_nastel_lists}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-bichuv_nastel_list_id}}',
            '{{%bichuv_doc}}',
            'bichuv_nastel_list_id',
            '{{%bichuv_nastel_lists}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_nastel_lists}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-bichuv_nastel_list_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `bichuv_nastel_list_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-bichuv_nastel_list_id}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'bichuv_nastel_list_id');
    }
}
