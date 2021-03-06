<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_slice_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 */
class m201008_150416_add_model_orders_items_id_column_to_bichuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_slice_item_balance}}', 'model_orders_items_id', $this->integer());

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_item_balance-model_orders_items_id}}',
            '{{%bichuv_slice_item_balance}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_item_balance-model_orders_items_id}}',
            '{{%bichuv_slice_item_balance}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_item_balance-model_orders_items_id}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_item_balance-model_orders_items_id}}',
            '{{%bichuv_slice_item_balance}}'
        );

        $this->dropColumn('{{%bichuv_slice_item_balance}}', 'model_orders_items_id');
    }
}
