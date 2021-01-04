<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_changes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 */
class m191125_123810_create_model_orders_items_changes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_changes}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'add_info' => $this->text(),
            'type' => $this->smallInteger(6)->defaultValue(1),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_changes-model_orders_items_id}}',
            '{{%model_orders_items_changes}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_changes-model_orders_items_id}}',
            '{{%model_orders_items_changes}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
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
            '{{%fk-model_orders_items_changes-model_orders_items_id}}',
            '{{%model_orders_items_changes}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_changes-model_orders_items_id}}',
            '{{%model_orders_items_changes}}'
        );

        $this->dropTable('{{%model_orders_items_changes}}');
    }
}
