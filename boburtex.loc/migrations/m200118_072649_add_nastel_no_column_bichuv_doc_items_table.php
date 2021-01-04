<?php

use yii\db\Migration;

/**
 * Class m200118_072649_add_nastel_no_column_bichuv_doc_items_table
 */
class m200118_072649_add_nastel_no_column_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','nastel_no', $this->string(25));

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-bichuv_doc_items-nastel_no}}',
            '{{%bichuv_doc_items}}',
            'nastel_no'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `nastel_no`
        $this->dropIndex(
            '{{%idx-bichuv_doc_items-nastel_no}}',
            '{{%bichuv_doc_items}}'
        );

        $this->dropColumn('bichuv_doc_items','nastel_no');
    }
}
