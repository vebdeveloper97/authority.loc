<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_pechat}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%model_orders_items}}`
 */
class m200708_125004_create_model_orders_items_pechat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_pechat}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'whom' => $this->text(),
            'width' => $this->char(255),
            'height' => $this->char(255),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_items_pechat-model_orders_id}}',
            '{{%model_orders_items_pechat}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_pechat-model_orders_id}}',
            '{{%model_orders_items_pechat}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_pechat-model_orders_items_id}}',
            '{{%model_orders_items_pechat}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_pechat-model_orders_items_id}}',
            '{{%model_orders_items_pechat}}',
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
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_pechat-model_orders_id}}',
            '{{%model_orders_items_pechat}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_pechat-model_orders_id}}',
            '{{%model_orders_items_pechat}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_pechat-model_orders_items_id}}',
            '{{%model_orders_items_pechat}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_pechat-model_orders_items_id}}',
            '{{%model_orders_items_pechat}}'
        );

        $this->dropTable('{{%model_orders_items_pechat}}');
    }
}
