<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_var_rotatsion_rel_attach}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_var_rotatsion}}`
 * - `{{%attachments}}`
 */
class m200619_130726_create_model_var_rotatsion_rel_attach_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_var_rotatsion_rel_attach}}', [
            'id' => $this->primaryKey(),
            'model_var_rotatsion_id' => $this->integer(),
            'attachment_id' => $this->integer(),
            'is_main' => $this->integer(1),
            'status' => $this->smallInteger(6),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `model_var_rotatsion`
        $this->createIndex(
            '{{%idx-model_var_rotatsion_rel_attach-model_var_rotatsion_id}}',
            '{{%model_var_rotatsion_rel_attach}}',
            'model_var_rotatsion_id'
        );

        // add foreign key for table `{{%model_var_rotatsion}}`
        $this->addForeignKey(
            '{{%fk-model_var_rotatsion_rel_attach-model_var_rotatsion_id}}',
            '{{%model_var_rotatsion_rel_attach}}',
            'model_var_rotatsion_id',
            '{{%model_var_rotatsion}}',
            'id',
            'CASCADE'
        );

        // creates index for column `attachment_id`
        $this->createIndex(
            '{{%idx-model_var_rotatsion_rel_attach-attachment_id}}',
            '{{%model_var_rotatsion_rel_attach}}',
            'attachment_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-model_var_rotatsion_rel_attach-attachment_id}}',
            '{{%model_var_rotatsion_rel_attach}}',
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
        // drops foreign key for table `{{%model_var_rotatsion}}`
        $this->dropForeignKey(
            '{{%fk-model_var_rotatsion_rel_attach-model_var_rotatsion_id}}',
            '{{%model_var_rotatsion_rel_attach}}'
        );

        // drops index for column `model_var_rotatsion`
        $this->dropIndex(
            '{{%idx-model_var_rotatsion_rel_attach-model_var_rotatsion_id}}',
            '{{%model_var_rotatsion_rel_attach}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-model_var_rotatsion_rel_attach-attachment_id}}',
            '{{%model_var_rotatsion_rel_attach}}'
        );

        // drops index for column `attachment_id`
        $this->dropIndex(
            '{{%idx-model_var_rotatsion_rel_attach-attachment_id}}',
            '{{%model_var_rotatsion_rel_attach}}'
        );

        $this->dropTable('{{%model_var_rotatsion_rel_attach}}');
    }
}
