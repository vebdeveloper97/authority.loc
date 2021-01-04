<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_status}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 */
class m200714_112011_create_model_orders_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_status}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'order_status' => $this->smallInteger()->defaultValue(1),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'type' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_status-model_orders_id}}',
            '{{%model_orders_status}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_status-model_orders_id}}',
            '{{%model_orders_status}}',
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
            '{{%fk-model_orders_status-model_orders_id}}',
            '{{%model_orders_status}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_status-model_orders_id}}',
            '{{%model_orders_status}}'
        );

        $this->dropTable('{{%model_orders_status}}');
    }
}
