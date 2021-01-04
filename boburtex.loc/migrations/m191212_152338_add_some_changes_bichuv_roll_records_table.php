<?php

use yii\db\Migration;

/**
 * Class m191212_152338_add_some_changes_bichuv_roll_records_table
 */
class m191212_152338_add_some_changes_bichuv_roll_records_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_roll_records','doc_item_id', $this->integer());

        // creates index for column `doc_item_id`
        $this->createIndex(
            '{{%idx-bichuv_roll_records-doc_item_id}}',
            '{{%bichuv_roll_records}}',
            'doc_item_id'
        );

        // add foreign key for table `{{%doc_item_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_roll_records-doc_item_id}}',
            '{{%bichuv_roll_records}}',
            'doc_item_id',
            '{{%bichuv_doc_items}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%doc_item_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_roll_records-doc_item_id}}',
            '{{%bichuv_roll_records}}'
        );

        // drops index for column `doc_item_id`
        $this->dropIndex(
            '{{%idx-bichuv_roll_records-doc_item_id}}',
            '{{%bichuv_roll_records}}'
        );
        $this->dropColumn('bichuv_roll_records','doc_item_id');
    }
}
