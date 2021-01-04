<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 */
class m200107_063831_add_toquv_doc_id_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc','toquv_doc_id', $this->integer());

        // creates index for column `toquv_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-toquv_doc_id}}',
            '{{%bichuv_doc}}',
            'toquv_doc_id'
        );

        // add foreign key for table `{{%toquv_documents}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-toquv_doc_id}}',
            '{{%bichuv_doc}}',
            'toquv_doc_id',
            '{{%toquv_documents}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_documents}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-toquv_doc_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `toquv_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-toquv_doc_id}}',
            '{{%bichuv_doc}}'
        );
        $this->dropColumn('bichuv_doc','toquv_doc_id');
    }
}
