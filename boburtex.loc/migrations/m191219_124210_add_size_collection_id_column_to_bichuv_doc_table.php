<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 */
class m191219_124210_add_size_collection_id_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc','size_collection_id', $this->integer());

        // creates index for column `size_collection_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-size_collection_id}}',
            '{{%bichuv_doc}}',
            'size_collection_id'
        );

        // add foreign key for table `{{%size_collection_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-size_collection_id}}',
            '{{%bichuv_doc}}',
            'size_collection_id',
            '{{%size_collections}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size_collection_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-size_collection_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `size_collection_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-size_collection_id}}',
            '{{%bichuv_doc}}'
        );
        $this->dropColumn('bichuv_doc','size_collection_id');
    }
}
