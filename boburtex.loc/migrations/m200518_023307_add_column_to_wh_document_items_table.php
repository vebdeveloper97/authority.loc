<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%wh_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%sell_pb}}`
 */
class m200518_023307_add_column_to_wh_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%wh_document_items}}', 'sell_price', $this->decimal(20,2));
        $this->addColumn('{{%wh_document_items}}', 'sell_pb_id', $this->integer());

        $this->addColumn('{{%wh_item_balance}}', 'sell_price', $this->decimal(20,2));
        $this->addColumn('{{%wh_item_balance}}', 'sell_pb_id', $this->integer());

        // creates index for column `sell_pb_id`
        $this->createIndex(
            '{{%idx-wh_document_items-sell_pb_id}}',
            '{{%wh_document_items}}',
            'sell_pb_id'
        );

        // add foreign key for table `{{%sell_pb}}`
        $this->addForeignKey(
            '{{%fk-wh_document_items-sell_pb_id}}',
            '{{%wh_document_items}}',
            'sell_pb_id',
            '{{%pul_birligi}}',
            'id'
        );

        // creates index for column `sell_pb_id`
        $this->createIndex(
            '{{%idx-wh_item_balance-sell_pb_id}}',
            '{{%wh_item_balance}}',
            'sell_pb_id'
        );

        // add foreign key for table `{{%sell_pb}}`
        $this->addForeignKey(
            '{{%fk-wh_item_balance-sell_pb_id}}',
            '{{%wh_item_balance}}',
            'sell_pb_id',
            '{{%pul_birligi}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%sell_pb}}`
        $this->dropForeignKey(
            '{{%fk-wh_document_items-sell_pb_id}}',
            '{{%wh_document_items}}'
        );

        // drops index for column `sell_pb_id`
        $this->dropIndex(
            '{{%idx-wh_document_items-sell_pb_id}}',
            '{{%wh_document_items}}'
        );

        // drops foreign key for table `{{%sell_pb}}`
        $this->dropForeignKey(
            '{{%fk-wh_item_balance-sell_pb_id}}',
            '{{%wh_item_balance}}'
        );

        // drops index for column `sell_pb_id`
        $this->dropIndex(
            '{{%idx-wh_item_balance-sell_pb_id}}',
            '{{%wh_item_balance}}'
        );

        $this->dropColumn('{{%wh_item_balance}}', 'sell_price');
        $this->dropColumn('{{%wh_item_balance}}', 'sell_pb_id');

        $this->dropColumn('{{%wh_document_items}}', 'sell_price');
        $this->dropColumn('{{%wh_document_items}}', 'sell_pb_id');
    }
}
