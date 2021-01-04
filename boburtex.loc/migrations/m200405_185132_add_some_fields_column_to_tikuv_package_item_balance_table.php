<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_package_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%order}}`
 * - `{{%order_item}}`
 */
class m200405_185132_add_some_fields_column_to_tikuv_package_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_package_item_balance}}', 'order_id', $this->integer());
        $this->addColumn('{{%tikuv_package_item_balance}}', 'order_item_id', $this->integer());

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-order_id}}',
            '{{%tikuv_package_item_balance}}',
            'order_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-order_id}}',
            '{{%tikuv_package_item_balance}}',
            'order_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `order_item_id`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-order_item_id}}',
            '{{%tikuv_package_item_balance}}',
            'order_item_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-order_item_id}}',
            '{{%tikuv_package_item_balance}}',
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
            '{{%fk-tikuv_package_item_balance-order_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-order_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-order_item_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `order_item_id`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-order_item_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        $this->dropColumn('{{%tikuv_package_item_balance}}', 'order_id');
        $this->dropColumn('{{%tikuv_package_item_balance}}', 'order_item_id');
    }
}
