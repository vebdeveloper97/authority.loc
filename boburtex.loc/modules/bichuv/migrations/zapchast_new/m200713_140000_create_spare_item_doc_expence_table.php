<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%spare_item_doc_expence}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%spare_item_doc}}`
 */
class m200713_140000_create_spare_item_doc_expence_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%spare_item_doc_expence}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'price' => $this->decimal(20,3),
            'pb_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `document_id`
        $this->createIndex(
            '{{%idx-spare_item_doc_expence-document_id}}',
            '{{%spare_item_doc_expence}}',
            'document_id'
        );

        // add foreign key for table `{{%spare_item_doc}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_expence-document_id}}',
            '{{%spare_item_doc_expence}}',
            'document_id',
            '{{%spare_item_doc}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%spare_item_doc}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_expence-document_id}}',
            '{{%spare_item_doc_expence}}'
        );

        // drops index for column `document_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc_expence-document_id}}',
            '{{%spare_item_doc_expence}}'
        );

        $this->dropTable('{{%spare_item_doc_expence}}');
    }
}
