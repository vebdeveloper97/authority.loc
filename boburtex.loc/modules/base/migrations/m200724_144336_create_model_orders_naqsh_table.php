<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_naqsh}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%attachments}}`
 */
class m200724_144336_create_model_orders_naqsh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_naqsh}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text(),
            'attachment_id' => $this->integer(),
            'add_info' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `attachment_id`
        $this->createIndex(
            '{{%idx-model_orders_naqsh-attachment_id}}',
            '{{%model_orders_naqsh}}',
            'attachment_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-model_orders_naqsh-attachment_id}}',
            '{{%model_orders_naqsh}}',
            'attachment_id',
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
        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_naqsh-attachment_id}}',
            '{{%model_orders_naqsh}}'
        );

        // drops index for column `attachment_id`
        $this->dropIndex(
            '{{%idx-model_orders_naqsh-attachment_id}}',
            '{{%model_orders_naqsh}}'
        );

        $this->dropTable('{{%model_orders_naqsh}}');
    }
}
