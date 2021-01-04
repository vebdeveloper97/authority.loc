<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_item_balance}}`.
 */
class m200114_125909_add_model_id_column_to_bichuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_slice_item_balance','model_id', $this->smallInteger(6));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_item_balance-model_id}}',
            '{{%bichuv_slice_item_balance}}',
            'model_id'
        );

        // add foreign key for table `{{%toquv_documents}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_item_balance-model_id}}',
            '{{%bichuv_slice_item_balance}}',
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
            '{{%fk-bichuv_slice_item_balance-model_id}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_item_balance-model_id}}',
            '{{%bichuv_slice_item_balance}}'
        );
        $this->dropColumn('bichuv_slice_item_balance','model_id');
    }
}
