<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_rel_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%order}}`
 * - `{{%order_item}}`
 * - `{{%pb}}`
 */
class m200405_121254_add_some_fields_column_to_model_rel_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-model_rel_production-order_id}}',
            '{{%model_rel_production}}',
            'order_id'
        );

        // add foreign key for table `{{%order}}`
        $this->addForeignKey(
            '{{%fk-model_rel_production-order_id}}',
            '{{%model_rel_production}}',
            'order_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `order_item_id`
        $this->createIndex(
            '{{%idx-model_rel_production-order_item_id}}',
            '{{%model_rel_production}}',
            'order_item_id'
        );

        // add foreign key for table `{{%order_item}}`
        $this->addForeignKey(
            '{{%fk-model_rel_production-order_item_id}}',
            '{{%model_rel_production}}',
            'order_item_id',
            '{{%model_orders_items}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_production-order_id}}',
            '{{%model_rel_production}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-model_rel_production-order_id}}',
            '{{%model_rel_production}}'
        );

        // drops foreign key for table `{{%model_order_items}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_production-order_item_id}}',
            '{{%model_rel_production}}'
        );

        // drops index for column `order_item_id`
        $this->dropIndex(
            '{{%idx-model_rel_production-order_item_id}}',
            '{{%model_rel_production}}'
        );
    }
}
