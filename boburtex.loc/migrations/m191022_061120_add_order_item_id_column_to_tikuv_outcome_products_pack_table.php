<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_outcome_products_pack}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 */
class m191022_061120_add_order_item_id_column_to_tikuv_outcome_products_pack_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_outcome_products_pack}}', 'order_item_id', $this->integer());

        // creates index for column `order_item_id`
        $this->createIndex(
            '{{%idx-tikuv_outcome_products_pack-order_item_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'order_item_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-tikuv_outcome_products_pack-order_item_id}}',
            '{{%tikuv_outcome_products_pack}}',
            'order_item_id',
            '{{%model_orders_items}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_outcome_products_pack-order_item_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        // drops index for column `order_item_id`
        $this->dropIndex(
            '{{%idx-tikuv_outcome_products_pack-order_item_id}}',
            '{{%tikuv_outcome_products_pack}}'
        );

        $this->dropColumn('{{%tikuv_outcome_products_pack}}', 'order_item_id');
    }
}
