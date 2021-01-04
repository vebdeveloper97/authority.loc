<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_naqsh}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%attachments}}`
 */
class m200815_075815_create_models_naqsh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models_naqsh}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'models_var_id' => $this->integer(),
            'title' => $this->char(255),
            'content' => $this->text(),
            'attachments_id' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-models_naqsh-models_list_id}}',
            '{{%models_naqsh}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-models_naqsh-models_list_id}}',
            '{{%models_naqsh}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );

        // creates index for column `models_var_id`
        $this->createIndex(
            '{{%idx-models_naqsh-models_var_id}}',
            '{{%models_naqsh}}',
            'models_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-models_naqsh-models_var_id}}',
            '{{%models_naqsh}}',
            'models_var_id',
            '{{%models_variations}}',
            'id',
            'CASCADE'
        );

        // creates index for column `attachments_id`
        $this->createIndex(
            '{{%idx-models_naqsh-attachments_id}}',
            '{{%models_naqsh}}',
            'attachments_id'
        );

        // add foreign key for table `{{%attachments}}`
        $this->addForeignKey(
            '{{%fk-models_naqsh-attachments_id}}',
            '{{%models_naqsh}}',
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
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-models_naqsh-models_list_id}}',
            '{{%models_naqsh}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-models_naqsh-models_list_id}}',
            '{{%models_naqsh}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-models_naqsh-models_var_id}}',
            '{{%models_naqsh}}'
        );

        // drops index for column `models_var_id`
        $this->dropIndex(
            '{{%idx-models_naqsh-models_var_id}}',
            '{{%models_naqsh}}'
        );

        // drops foreign key for table `{{%attachments}}`
        $this->dropForeignKey(
            '{{%fk-models_naqsh-attachments_id}}',
            '{{%models_naqsh}}'
        );

        // drops index for column `attachments_id`
        $this->dropIndex(
            '{{%idx-models_naqsh-attachments_id}}',
            '{{%models_naqsh}}'
        );

        $this->dropTable('{{%models_naqsh}}');
    }
}
