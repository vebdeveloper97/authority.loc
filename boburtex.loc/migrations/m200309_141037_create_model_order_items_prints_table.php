<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_order_items_prints}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%model_var_prints}}`
 */
class m200309_141037_create_model_order_items_prints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_order_items_prints}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'model_var_prints_id' => $this->integer(),
            'add_info' => $this->string(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_order_items_prints-model_orders_items_id}}',
            '{{%model_order_items_prints}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_prints-model_orders_items_id}}',
            '{{%model_order_items_prints}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_prints_id`
        $this->createIndex(
            '{{%idx-model_order_items_prints-model_var_prints_id}}',
            '{{%model_order_items_prints}}',
            'model_var_prints_id'
        );

        // add foreign key for table `{{%model_var_prints}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_prints-model_var_prints_id}}',
            '{{%model_order_items_prints}}',
            'model_var_prints_id',
            '{{%model_var_prints}}',
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
            '{{%fk-model_order_items_prints-model_orders_items_id}}',
            '{{%model_order_items_prints}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_order_items_prints-model_orders_items_id}}',
            '{{%model_order_items_prints}}'
        );

        // drops foreign key for table `{{%model_var_prints}}`
        $this->dropForeignKey(
            '{{%fk-model_order_items_prints-model_var_prints_id}}',
            '{{%model_order_items_prints}}'
        );

        // drops index for column `model_var_prints_id`
        $this->dropIndex(
            '{{%idx-model_order_items_prints-model_var_prints_id}}',
            '{{%model_order_items_prints}}'
        );

        $this->dropTable('{{%model_order_items_prints}}');
    }
}
