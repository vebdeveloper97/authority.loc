<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_rel_attach}}`.
 */
class m190905_155303_create_model_rel_attach_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%model_rel_attach}}', [
            'id' => $this->primaryKey(),
            'attachment_id' => $this->integer(),
            'model_list_id' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1),
            'is_main' => $this->boolean(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //attachment_id
        $this->createIndex(
            'idx-model_rel_attach-attachment_id',
            'model_rel_attach',
            'attachment_id'
        );

        $this->addForeignKey(
            'fk-model_rel_attach-attachment_id',
            'model_rel_attach',
            'attachment_id',
            'attachments',
            'id'
        );

        //model_list_id
        $this->createIndex(
            'idx-model_rel_attach-model_list_id',
            'model_rel_attach',
            'model_list_id'
        );

        $this->addForeignKey(
            'fk-model_rel_attach-model_list_id',
            'model_rel_attach',
            'model_list_id',
            'models_list',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //model_list_id
        $this->dropForeignKey(
            'fk-model_rel_attach-model_list_id',
            'model_rel_attach'
        );

        $this->dropIndex(
            'idx-model_rel_attach-model_list_id',
            'model_rel_attach'
        );

        //attachment_id
        $this->dropForeignKey(
            'fk-model_rel_attach-attachment_id',
            'model_rel_attach'
        );

        $this->dropIndex(
            'idx-model_rel_attach-attachment_id',
            'model_rel_attach'
        );
        $this->dropTable('{{%model_rel_attach}}');
    }
}
