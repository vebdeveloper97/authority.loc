<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_roll_records}}`.
 */
class m191211_100703_create_bichuv_roll_records_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_roll_records}}', [
            'id' => $this->primaryKey(),
            'bichuv_sub_doc_id' => $this->integer(),
            'quantity' => $this->decimal(5,3),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'reg_date' => $this->dateTime(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        // creates index for column `bichuv_sub_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_roll_records-bichuv_sub_doc_id}}',
            '{{%bichuv_roll_records}}',
            'bichuv_sub_doc_id'
        );

        // add foreign key for table `{{%bichuv_sub_doc_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_roll_records-bichuv_sub_doc_id}}',
            '{{%bichuv_roll_records}}',
            'bichuv_sub_doc_id',
            '{{%bichuv_sub_doc_items}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_sub_doc_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_roll_records-bichuv_sub_doc_id}}',
            '{{%bichuv_roll_records}}'
        );

        // drops index for column `bichuv_sub_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_roll_records-bichuv_sub_doc_id}}',
            '{{%bichuv_roll_records}}'
        );

        $this->dropTable('{{%bichuv_roll_records}}');
    }
}
