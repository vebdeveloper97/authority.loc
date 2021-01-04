<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_rel_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%order}}`
 * - `{{%order_item}}`
 * - `{{%pb}}`
 */
class m200405_121254_add_some_fields_column_to_model_rel_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_rel_doc}}', 'order_id', $this->integer());
        $this->addColumn('{{%model_rel_doc}}', 'order_item_id', $this->integer());
        $this->addColumn('{{%model_rel_doc}}', 'price', $this->decimal(20,3)->defaultValue(0));
        $this->addColumn('{{%model_rel_doc}}', 'pb_id', $this->integer());
        $this->addColumn('{{%model_rel_production}}', 'is_accepted', $this->boolean());

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-model_rel_doc-order_id}}',
            '{{%model_rel_doc}}',
            'order_id'
        );

        // add foreign key for table `{{%order}}`
        $this->addForeignKey(
            '{{%fk-model_rel_doc-order_id}}',
            '{{%model_rel_doc}}',
            'order_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `order_item_id`
        $this->createIndex(
            '{{%idx-model_rel_doc-order_item_id}}',
            '{{%model_rel_doc}}',
            'order_item_id'
        );

        // add foreign key for table `{{%order_item}}`
        $this->addForeignKey(
            '{{%fk-model_rel_doc-order_item_id}}',
            '{{%model_rel_doc}}',
            'order_item_id',
            '{{%model_orders_items}}',
            'id'
        );

        // creates index for column `pb_id`
        $this->createIndex(
            '{{%idx-model_rel_doc-pb_id}}',
            '{{%model_rel_doc}}',
            'pb_id'
        );

        // add foreign key for table `{{%pb}}`
        $this->addForeignKey(
            '{{%fk-model_rel_doc-pb_id}}',
            '{{%model_rel_doc}}',
            'pb_id',
            '{{%pul_birligi}}',
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
            '{{%fk-model_rel_doc-order_id}}',
            '{{%model_rel_doc}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-model_rel_doc-order_id}}',
            '{{%model_rel_doc}}'
        );

        // drops foreign key for table `{{%model_order_items}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_doc-order_item_id}}',
            '{{%model_rel_doc}}'
        );

        // drops index for column `order_item_id`
        $this->dropIndex(
            '{{%idx-model_rel_doc-order_item_id}}',
            '{{%model_rel_doc}}'
        );

        // drops foreign key for table `{{%pul_birligi}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_doc-pb_id}}',
            '{{%model_rel_doc}}'
        );

        // drops index for column `pb_id`
        $this->dropIndex(
            '{{%idx-model_rel_doc-pb_id}}',
            '{{%model_rel_doc}}'
        );

        $this->dropColumn('{{%model_rel_doc}}', 'order_id');
        $this->dropColumn('{{%model_rel_doc}}', 'order_item_id');
        $this->dropColumn('{{%model_rel_doc}}', 'price');
        $this->dropColumn('{{%model_rel_doc}}', 'pb_id');
        $this->dropColumn('{{%model_rel_production}}', 'is_accepted');
    }
}
