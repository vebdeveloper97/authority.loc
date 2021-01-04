<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%wh_document_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%wh_item_balance}}`
 */
class m200506_120656_add_some_columns_to_wh_document_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%wh_document_items}}', 'wh_item_balance_id', $this->integer());

        // creates index for column `wh_item_balance_id`
        $this->createIndex(
            '{{%idx-wh_document_items-wh_item_balance_id}}',
            '{{%wh_document_items}}',
            'wh_item_balance_id'
        );

        // add foreign key for table `{{%wh_item_balance}}`
        $this->addForeignKey(
            '{{%fk-wh_document_items-wh_item_balance_id}}',
            '{{%wh_document_items}}',
            'wh_item_balance_id',
            '{{%wh_item_balance}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%wh_item_balance}}`
        $this->dropForeignKey(
            '{{%fk-wh_document_items-wh_item_balance_id}}',
            '{{%wh_document_items}}'
        );

        // drops index for column `wh_item_balance_id`
        $this->dropIndex(
            '{{%idx-wh_document_items-wh_item_balance_id}}',
            '{{%wh_document_items}}'
        );

        $this->dropColumn('{{%wh_document_items}}', 'wh_item_balance_id');
    }
}
