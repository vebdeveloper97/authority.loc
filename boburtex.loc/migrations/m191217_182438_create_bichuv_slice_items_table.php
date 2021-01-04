<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_slice_items}}`.
 */
class m191217_182438_create_bichuv_slice_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_slice_items}}', [
            'id' => $this->primaryKey(),
            'size_id' => $this->integer(),
            'bichuv_doc_id' => $this->integer(),
            'nastel_party' => $this->string(50),
            'quantity' => $this->decimal(20,3),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'work_weight' => $this->decimal(10,3),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_items-size_id}}',
            '{{%bichuv_slice_items}}',
            'size_id'
        );

        // add foreign key for table `{{%size_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_items-size_id}}',
            '{{%bichuv_slice_items}}',
            'size_id',
            '{{%size}}',
            'id'
        );

        // creates index for column `bichuv_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_items-bichuv_doc_id}}',
            '{{%bichuv_slice_items}}',
            'bichuv_doc_id'
        );

        // add foreign key for table `{{%bichuv_doc_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_items-bichuv_doc_id}}',
            '{{%bichuv_slice_items}}',
            'bichuv_doc_id',
            '{{%bichuv_doc}}',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_items-size_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_items-size_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops foreign key for table `{{%bichuv_doc_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_items-bichuv_doc_id}}',
            '{{%bichuv_slice_items}}'
        );

        // drops index for column `bichuv_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_items-bichuv_doc_id}}',
            '{{%bichuv_slice_items}}'
        );

        $this->dropTable('{{%bichuv_slice_items}}');
    }
}
