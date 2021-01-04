<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_acs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%bichuv_acs}}`
 */
class m200319_055350_create_model_orders_items_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_acs}}', [
            'id' => $this->primaryKey(),
            'models_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'bichuv_acs_id' => $this->integer(),
            'qty' => $this->decimal(20,3),
            'unit_id' => $this->smallInteger()->defaultValue(2),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_acs-model_orders_items_id}}',
            '{{%model_orders_items_acs}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_acs-model_orders_items_id}}',
            '{{%model_orders_items_acs}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `bichuv_acs_id`
        $this->createIndex(
            '{{%idx-model_orders_items_acs-bichuv_acs_id}}',
            '{{%model_orders_items_acs}}',
            'bichuv_acs_id'
        );

        // add foreign key for table `{{%bichuv_acs}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_acs-bichuv_acs_id}}',
            '{{%model_orders_items_acs}}',
            'bichuv_acs_id',
            '{{%bichuv_acs}}',
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
            '{{%fk-model_orders_items_acs-model_orders_items_id}}',
            '{{%model_orders_items_acs}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_acs-model_orders_items_id}}',
            '{{%model_orders_items_acs}}'
        );

        // drops foreign key for table `{{%bichuv_acs}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_acs-bichuv_acs_id}}',
            '{{%model_orders_items_acs}}'
        );

        // drops index for column `bichuv_acs_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_acs-bichuv_acs_id}}',
            '{{%model_orders_items_acs}}'
        );

        $this->dropTable('{{%model_orders_items_acs}}');
    }
}
