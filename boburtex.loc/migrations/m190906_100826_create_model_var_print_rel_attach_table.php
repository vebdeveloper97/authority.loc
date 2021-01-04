<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_print_rel_attach}}`.
 */
class m190906_100826_create_model_var_print_rel_attach_table extends Migration
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
        $this->createTable('{{%model_var_print_rel_attach}}', [
            'id' => $this->primaryKey(),
            'model_var_print_id' => $this->integer(),
            'attachment_id' => $this->integer(),
            'is_main' => $this->boolean(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //attachment_id
        $this->createIndex(
            'idx-model_var_print_rel_attach-attachment_id',
            'model_var_print_rel_attach',
            'attachment_id'
        );

        $this->addForeignKey(
            'fk-model_var_print_rel_attach-attachment_id',
            'model_var_print_rel_attach',
            'attachment_id',
            'attachments',
            'id'
        );

        //model_var_print_id
        $this->createIndex(
            'idx-model_var_print_rel_attach-model_var_print_id',
            'model_var_print_rel_attach',
            'model_var_print_id'
        );

        $this->addForeignKey(
            'fk-model_var_print_rel_attach-model_var_print_id',
            'model_var_print_rel_attach',
            'model_var_print_id',
            'model_var_prints',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //model_var_print_id
        $this->dropForeignKey(
            'fk-model_var_print_rel_attach-model_var_print_id',
            'model_var_print_rel_attach'
        );

        $this->dropIndex(
            'idx-model_var_print_rel_attach-model_var_print_id',
            'model_var_print_rel_attach'
        );

        //attachment_id
        $this->dropForeignKey(
            'fk-model_var_print_rel_attach-attachment_id',
            'model_var_print_rel_attach'
        );

        $this->dropIndex(
            'idx-model_var_print_rel_attach-attachment_id',
            'model_var_print_rel_attach'
        );
        $this->dropTable('{{%model_var_print_rel_attach}}');
    }
}
