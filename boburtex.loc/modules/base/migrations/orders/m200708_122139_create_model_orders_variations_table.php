<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 */
class m200708_122139_create_model_orders_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_variations}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'variant_no' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_variations-model_orders_id}}',
            '{{%model_orders_variations}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_variations-model_orders_id}}',
            '{{%model_orders_variations}}',
            'model_orders_id',
            '{{%model_orders}}',
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
            '{{%fk-model_orders_variations-model_orders_id}}',
            '{{%model_orders_variations}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_variations-model_orders_id}}',
            '{{%model_orders_variations}}'
        );

        $this->dropTable('{{%model_orders_variations}}');
    }
}
