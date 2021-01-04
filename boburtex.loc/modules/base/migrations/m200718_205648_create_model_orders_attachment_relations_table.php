<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_attachment_relations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%attachments}}`
 */
class m200718_205648_create_model_orders_attachment_relations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_attachment_relations}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'attachments_id' => $this->integer(),
            'status' => $this->integer(),
            'create_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_attachment_relations-model_orders_items_id}}',
            '{{%model_orders_attachment_relations}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_attachment_relations-model_orders_items_id}}',
            '{{%model_orders_attachment_relations}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `attachments_id`
        $this->createIndex(
            '{{%idx-model_orders_attachment_relations-attachments_id}}',
            '{{%model_orders_attachment_relations}}',
            'attachments_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-model_orders_attachment_relations-attachments_id}}',
            '{{%model_orders_attachment_relations}}',
            'attachments_id',
            '{{%attachments}}',
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
            '{{%fk-model_orders_attachment_relations-model_orders_items_id}}',
            '{{%model_orders_attachment_relations}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_attachment_relations-model_orders_items_id}}',
            '{{%model_orders_attachment_relations}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_attachment_relations-attachments_id}}',
            '{{%model_orders_attachment_relations}}'
        );

        // drops index for column `attachments_id`
        $this->dropIndex(
            '{{%idx-model_orders_attachment_relations-attachments_id}}',
            '{{%model_orders_attachment_relations}}'
        );

        $this->dropTable('{{%model_orders_attachment_relations}}');
    }
}
