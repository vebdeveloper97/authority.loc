<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_fs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%model_orders_items}}`
 */
class m200806_174213_create_model_orders_fs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_fs}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'add_info' => $this->text(),
            'who_sewed' => $this->char(100),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_fs-model_orders_id}}',
            '{{%model_orders_fs}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_fs-model_orders_id}}',
            '{{%model_orders_fs}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_fs-model_orders_items_id}}',
            '{{%model_orders_fs}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_fs-model_orders_items_id}}',
            '{{%model_orders_fs}}',
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
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_fs-model_orders_id}}',
            '{{%model_orders_fs}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_fs-model_orders_id}}',
            '{{%model_orders_fs}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_fs-model_orders_items_id}}',
            '{{%model_orders_fs}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_fs-model_orders_items_id}}',
            '{{%model_orders_fs}}'
        );

        $this->dropTable('{{%model_orders_fs}}');
    }
}
