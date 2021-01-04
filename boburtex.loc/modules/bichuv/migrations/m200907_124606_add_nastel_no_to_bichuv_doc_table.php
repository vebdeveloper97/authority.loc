<?php

use yii\db\Migration;

/**
 * Class m200907_124606_add_nastel_no_to_bichuv_doc_table
 */
class m200907_124606_add_nastel_no_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc','nastel_no', $this->string(15));

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-bichuv_doc-nastel_no}}',
            '{{%bichuv_doc}}',
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
            '{{%idx-bichuv_doc-nastel_no}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('bichuv_doc','nastel_no');
    }


}
