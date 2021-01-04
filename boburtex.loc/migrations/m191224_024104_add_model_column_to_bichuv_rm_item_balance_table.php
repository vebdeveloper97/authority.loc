<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_rm_item_balance}}`.
 */
class m191224_024104_add_model_column_to_bichuv_rm_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_rm_item_balance','model_id', $this->smallInteger(6));
        $this->addColumn('bichuv_doc_items','model_id', $this->smallInteger(6));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-model_id}}',
            '{{%bichuv_rm_item_balance}}',
            'model_id'
        );

        // add foreign key for table `{{%model_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-model_id}}',
            '{{%bichuv_rm_item_balance}}',
            'model_id',
            '{{%product}}',
            'id'
        );

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_doc_items-model_id}}',
            '{{%bichuv_doc_items}}',
            'model_id'
        );

        // add foreign key for table `{{%model_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc_items-model_id}}',
            '{{%bichuv_doc_items}}',
            'model_id',
            '{{%product}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-model_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-model_id}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops foreign key for table `{{%model_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc_items-model_id}}',
            '{{%bichuv_doc_items}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc_items-model_id}}',
            '{{%bichuv_doc_items}}'
        );

        $this->dropColumn('bichuv_rm_item_balance','model_id');
        $this->dropColumn('bichuv_doc_items','model_id');
    }
}
