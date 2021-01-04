<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_size}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%size}}`
 */
class m190924_065426_create_model_orders_items_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_size}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'count' => $this->integer(),
            'size_id' => $this->integer(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_size-model_orders_items_id}}',
            '{{%model_orders_items_size}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_size-model_orders_items_id}}',
            '{{%model_orders_items_size}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-model_orders_items_size-size_id}}',
            '{{%model_orders_items_size}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_size-size_id}}',
            '{{%model_orders_items_size}}',
            'size_id',
            '{{%size}}',
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
            '{{%fk-model_orders_items_size-model_orders_items_id}}',
            '{{%model_orders_items_size}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_size-model_orders_items_id}}',
            '{{%model_orders_items_size}}'
        );

        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_size-size_id}}',
            '{{%model_orders_items_size}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_size-size_id}}',
            '{{%model_orders_items_size}}'
        );

        $this->dropTable('{{%model_orders_items_size}}');
    }
}
