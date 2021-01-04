<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 */
class m190923_195345_create_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'models_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'add_info' => $this->text(),
            'load_date' => $this->dateTime(),
            'priority' => $this->integer(),
            'season' => $this->string(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_items-model_orders_id}}',
            '{{%model_orders_items}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items-model_orders_id}}',
            '{{%model_orders_items}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_orders_items-models_list_id}}',
            '{{%model_orders_items}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items-models_list_id}}',
            '{{%model_orders_items}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-model_orders_items-model_var_id}}',
            '{{%model_orders_items}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items-model_var_id}}',
            '{{%model_orders_items}}',
            'model_var_id',
            '{{%models_variations}}',
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
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items-model_orders_id}}',
            '{{%model_orders_items}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_items-model_orders_id}}',
            '{{%model_orders_items}}'
        );

        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items-models_list_id}}',
            '{{%model_orders_items}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_orders_items-models_list_id}}',
            '{{%model_orders_items}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items-model_var_id}}',
            '{{%model_orders_items}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-model_orders_items-model_var_id}}',
            '{{%model_orders_items}}'
        );

        $this->dropTable('{{%model_orders_items}}');
    }
}
