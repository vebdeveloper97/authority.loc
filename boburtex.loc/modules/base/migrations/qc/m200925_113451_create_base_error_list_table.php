<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_error_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_error_category}}`
 */
class m200925_113451_create_base_error_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_error_list}}', [
            'id' => $this->primaryKey(),
            'error_category_id' => $this->integer(),
            'name' => $this->string(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `error_category_id`
        $this->createIndex(
            '{{%idx-base_error_list-error_category_id}}',
            '{{%base_error_list}}',
            'error_category_id'
        );

        // add foreign key for table `{{%base_error_category}}`
        $this->addForeignKey(
            '{{%fk-base_error_list-error_category_id}}',
            '{{%base_error_list}}',
            'error_category_id',
            '{{%base_error_category}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_error_category}}`
        $this->dropForeignKey(
            '{{%fk-base_error_list-error_category_id}}',
            '{{%base_error_list}}'
        );

        // drops index for column `error_category_id`
        $this->dropIndex(
            '{{%idx-base_error_list-error_category_id}}',
            '{{%base_error_list}}'
        );

        $this->dropTable('{{%base_error_list}}');
    }
}
