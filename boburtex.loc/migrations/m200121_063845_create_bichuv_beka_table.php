<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_beka}}`.
 */
class m200121_063845_create_bichuv_beka_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_beka}}', [
            'id' => $this->primaryKey(),
            'bichuv_doc_id' => $this->integer(),
            'weight' => $this->decimal(20,3)->defaultValue(0),
            'type' => $this->smallInteger(2)->defaultValue(1),
            'entity_id' => $this->integer(),
            'nastel_no' => $this->string(20),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_beka-bichuv_doc_id}}',
            '{{%bichuv_beka}}',
            'bichuv_doc_id'
        );

        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-bichuv_beka-bichuv_doc_id}}',
            '{{%bichuv_beka}}',
            'bichuv_doc_id',
            '{{%bichuv_doc}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_doc_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-bichuv_doc_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `bichuv_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-bichuv_doc_id}}',
            '{{%bichuv_doc}}'
        );
        $this->dropTable('{{%bichuv_beka}}');
    }
}
