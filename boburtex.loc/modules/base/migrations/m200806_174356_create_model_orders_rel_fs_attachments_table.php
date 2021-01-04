<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_rel_fs_attachments}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_fs}}`
 * - `{{%attachments}}`
 */
class m200806_174356_create_model_orders_rel_fs_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_rel_fs_attachments}}', [
            'id' => $this->primaryKey(),
            'model_orders_fs_id' => $this->integer(),
            'attachments_id' => $this->integer(),
        ]);

        // creates index for column `model_orders_fs_id`
        $this->createIndex(
            '{{%idx-model_orders_rel_fs_attachments-model_orders_fs_id}}',
            '{{%model_orders_rel_fs_attachments}}',
            'model_orders_fs_id'
        );

        // add foreign key for table `{{%model_orders_fs}}`
        $this->addForeignKey(
            '{{%fk-model_orders_rel_fs_attachments-model_orders_fs_id}}',
            '{{%model_orders_rel_fs_attachments}}',
            'model_orders_fs_id',
            '{{%model_orders_fs}}',
            'id',
            'CASCADE'
        );

        // creates index for column `attachments_id`
        $this->createIndex(
            '{{%idx-model_orders_rel_fs_attachments-attachments_id}}',
            '{{%model_orders_rel_fs_attachments}}',
            'attachments_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-model_orders_rel_fs_attachments-attachments_id}}',
            '{{%model_orders_rel_fs_attachments}}',
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
        // drops foreign key for table `{{%model_orders_fs}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_rel_fs_attachments-model_orders_fs_id}}',
            '{{%model_orders_rel_fs_attachments}}'
        );

        // drops index for column `model_orders_fs_id`
        $this->dropIndex(
            '{{%idx-model_orders_rel_fs_attachments-model_orders_fs_id}}',
            '{{%model_orders_rel_fs_attachments}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_rel_fs_attachments-attachments_id}}',
            '{{%model_orders_rel_fs_attachments}}'
        );

        // drops index for column `attachments_id`
        $this->dropIndex(
            '{{%idx-model_orders_rel_fs_attachments-attachments_id}}',
            '{{%model_orders_rel_fs_attachments}}'
        );

        $this->dropTable('{{%model_orders_rel_fs_attachments}}');
    }
}
