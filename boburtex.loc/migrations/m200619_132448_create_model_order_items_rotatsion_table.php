<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_order_items_rotatsion}}`.
 * - `{{%model_orders_items}}`
 * - `{{%model_var_rotatsion}}`
 */
class m200619_132448_create_model_order_items_rotatsion_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_order_items_rotatsion}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'model_var_rotatsion_id' => $this->integer(),
            'add_info' => $this->string(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_order_items_rotatsion-model_orders_items_id}}',
            '{{%model_order_items_rotatsion}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_rotatsion-model_orders_items_id}}',
            '{{%model_order_items_rotatsion}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_rotatsion_id`
        $this->createIndex(
            '{{%idx-model_order_items_rotatsion-model_var_rotatsion_id}}',
            '{{%model_order_items_rotatsion}}',
            'model_var_rotatsion_id'
        );

        // add foreign key for table `{{%model_var_rotatsion}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_rotatsion-model_var_rotatsion_id}}',
            '{{%model_order_items_rotatsion}}',
            'model_var_rotatsion_id',
            '{{%model_var_rotatsion}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_order_items_rotatsion-model_orders_items_id}}',
            '{{%model_order_items_rotatsion}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_order_items_rotatsion-model_orders_items_id}}',
            '{{%model_order_items_rotatsion}}'
        );

        // drops foreign key for table `{{%model_var_rotatsion}}`
        $this->dropForeignKey(
            '{{%fk-model_order_items_rotatsion-model_var_rotatsion_id}}',
            '{{%model_order_items_rotatsion}}'
        );

        // drops index for column `model_var_rotatsion_id`
        $this->dropIndex(
            '{{%idx-model_order_items_rotatsion-model_var_rotatsion_id}}',
            '{{%model_order_items_rotatsion}}'
        );

        $this->dropTable('{{%model_order_items_rotatsion}}');
    }
}
