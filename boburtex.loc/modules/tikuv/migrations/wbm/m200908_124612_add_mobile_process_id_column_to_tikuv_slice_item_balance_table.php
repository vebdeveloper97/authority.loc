<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_slice_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process}}`
 */
class m200908_124612_add_mobile_process_id_column_to_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_slice_item_balance}}', 'mobile_process_id', $this->integer());

        // creates index for column `mobile_process_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-mobile_process_id}}',
            '{{%tikuv_slice_item_balance}}',
            'mobile_process_id'
        );

        // add foreign key for table `{{%mobile_process}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-mobile_process_id}}',
            '{{%tikuv_slice_item_balance}}',
            'mobile_process_id',
            '{{%mobile_process}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_process}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-mobile_process_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `mobile_process_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-mobile_process_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        $this->dropColumn('{{%tikuv_slice_item_balance}}', 'mobile_process_id');
    }
}
