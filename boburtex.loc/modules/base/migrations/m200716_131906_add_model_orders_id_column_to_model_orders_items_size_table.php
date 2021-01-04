<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_size}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 */
class m200716_131906_add_model_orders_id_column_to_model_orders_items_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_size}}', 'model_orders_id', $this->integer());

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_items_size-model_orders_id}}',
            '{{%model_orders_items_size}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_size-model_orders_id}}',
            '{{%model_orders_items_size}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_size-model_orders_id}}',
            '{{%model_orders_items_size}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_size-model_orders_id}}',
            '{{%model_orders_items_size}}'
        );

        $this->dropColumn('{{%model_orders_items_size}}', 'model_orders_id');
    }
}
