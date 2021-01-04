<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_slice_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_tables}}`
 */
class m200910_181012_add_mobile_tables_id_column_to_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_slice_item_balance}}', 'mobile_tables_id', $this->integer());

        // creates index for column `mobile_tables_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-mobile_tables_id}}',
            '{{%tikuv_slice_item_balance}}',
            'mobile_tables_id'
        );

        // add foreign key for table `{{%mobile_tables}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-mobile_tables_id}}',
            '{{%tikuv_slice_item_balance}}',
            'mobile_tables_id',
            '{{%mobile_tables}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_tables}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-mobile_tables_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `mobile_tables_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-mobile_tables_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        $this->dropColumn('{{%tikuv_slice_item_balance}}', 'mobile_tables_id');
    }
}
