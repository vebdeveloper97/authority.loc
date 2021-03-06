<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_model_sizes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%size}}`
 * - `{{%base_model_document}}`
 * - `{{%base_model_document_items}}`
 */
class m200929_112911_create_base_model_sizes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_model_sizes}}', [
            'id' => $this->primaryKey(),
            'size_id' => $this->integer(),
            'doc_id' => $this->integer(),
            'doc_items_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-base_model_sizes-size_id}}',
            '{{%base_model_sizes}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-base_model_sizes-size_id}}',
            '{{%base_model_sizes}}',
            'size_id',
            '{{%size}}',
            'id',
            'CASCADE'
        );

        // creates index for column `doc_id`
        $this->createIndex(
            '{{%idx-base_model_sizes-doc_id}}',
            '{{%base_model_sizes}}',
            'doc_id'
        );

        // add foreign key for table `{{%base_model_document}}`
        $this->addForeignKey(
            '{{%fk-base_model_sizes-doc_id}}',
            '{{%base_model_sizes}}',
            'doc_id',
            '{{%base_model_document}}',
            'id',
            'CASCADE'
        );

        // creates index for column `doc_items_id`
        $this->createIndex(
            '{{%idx-base_model_sizes-doc_items_id}}',
            '{{%base_model_sizes}}',
            'doc_items_id'
        );

        // add foreign key for table `{{%base_model_document_items}}`
        $this->addForeignKey(
            '{{%fk-base_model_sizes-doc_items_id}}',
            '{{%base_model_sizes}}',
            'doc_items_id',
            '{{%base_model_document_items}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-base_model_sizes-size_id}}',
            '{{%base_model_sizes}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-base_model_sizes-size_id}}',
            '{{%base_model_sizes}}'
        );

        // drops foreign key for table `{{%base_model_document}}`
        $this->dropForeignKey(
            '{{%fk-base_model_sizes-doc_id}}',
            '{{%base_model_sizes}}'
        );

        // drops index for column `doc_id`
        $this->dropIndex(
            '{{%idx-base_model_sizes-doc_id}}',
            '{{%base_model_sizes}}'
        );

        // drops foreign key for table `{{%base_model_document_items}}`
        $this->dropForeignKey(
            '{{%fk-base_model_sizes-doc_items_id}}',
            '{{%base_model_sizes}}'
        );

        // drops index for column `doc_items_id`
        $this->dropIndex(
            '{{%idx-base_model_sizes-doc_items_id}}',
            '{{%base_model_sizes}}'
        );

        $this->dropTable('{{%base_model_sizes}}');
    }
}
