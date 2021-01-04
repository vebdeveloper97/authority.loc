<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_rm_item_balance}}`.
 */
class m191225_033334_add_doc_id_column_to_bichuv_rm_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_rm_item_balance','doc_id', $this->integer());

        // creates index for column `doc_id`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-doc_id}}',
            '{{%bichuv_rm_item_balance}}',
            'doc_id'
        );

        // add foreign key for table `{{%doc_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-doc_id}}',
            '{{%bichuv_rm_item_balance}}',
            'doc_id',
            '{{%bichuv_doc}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%doc_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-doc_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-doc_id}}',
            '{{%bichuv_rm_item_balance}}'
        );
        $this->dropColumn('bichuv_rm_item_balance','doc_id');
    }
}
