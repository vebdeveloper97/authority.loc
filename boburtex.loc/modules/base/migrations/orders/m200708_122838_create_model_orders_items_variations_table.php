<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%model_orders_items}}`
 * - `{{%color_pantone}}`
 */
class m200708_122838_create_model_orders_items_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_variations}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'name' => $this->text(),
            'color_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_items_variations-model_orders_id}}',
            '{{%model_orders_items_variations}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_variations-model_orders_id}}',
            '{{%model_orders_items_variations}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_variations-model_orders_items_id}}',
            '{{%model_orders_items_variations}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_variations-model_orders_items_id}}',
            '{{%model_orders_items_variations}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id'
        );

        // creates index for column `color_id`
        $this->createIndex(
            '{{%idx-model_orders_items_variations-color_id}}',
            '{{%model_orders_items_variations}}',
            'color_id'
        );

        // add foreign key for table `{{%color_pantone}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_variations-color_id}}',
            '{{%model_orders_items_variations}}',
            'color_id',
            '{{%color_pantone}}',
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
            '{{%fk-model_orders_items_variations-model_orders_id}}',
            '{{%model_orders_items_variations}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_variations-model_orders_id}}',
            '{{%model_orders_items_variations}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_variations-model_orders_items_id}}',
            '{{%model_orders_items_variations}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_variations-model_orders_items_id}}',
            '{{%model_orders_items_variations}}'
        );

        // drops foreign key for table `{{%color_pantone}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_variations-color_id}}',
            '{{%model_orders_items_variations}}'
        );

        // drops index for column `color_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_variations-color_id}}',
            '{{%model_orders_items_variations}}'
        );

        $this->dropTable('{{%model_orders_items_variations}}');
    }
}
