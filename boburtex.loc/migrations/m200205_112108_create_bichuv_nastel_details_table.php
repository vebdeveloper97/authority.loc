<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_details}}`.
 */
class m200205_112108_create_bichuv_nastel_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_details}}', [
            'id' => $this->primaryKey(),
            'bichuv_doc_id' => $this->integer(),
            'detail_type_id' => $this->integer(),
            'nastel_no' => $this->string(20),
            'count' => $this->integer(6)->defaultValue(0),
            'weight' => $this->decimal(20,3)->defaultValue(0),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_doc_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-bichuv_doc_id}}',
            '{{%bichuv_nastel_details}}',
            'bichuv_doc_id'
        );

        // add foreign key for table `{{%bichuv_doc_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_details-bichuv_doc_id}}',
            '{{%bichuv_nastel_details}}',
            'bichuv_doc_id',
            '{{%bichuv_doc}}',
            'id'
        );

        // creates index for column `detail_type_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-detail_type_id}}',
            '{{%bichuv_nastel_details}}',
            'detail_type_id'
        );

        // add foreign key for table `{{%detail_type_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_details-detail_type_id}}',
            '{{%bichuv_nastel_details}}',
            'detail_type_id',
            '{{%bichuv_detail_types}}',
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
            '{{%fk-bichuv_nastel_details-bichuv_doc_id}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops index for column `bichuv_doc_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-bichuv_doc_id}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops foreign key for table `{{%detail_type_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_details-detail_type_id}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops index for column `detail_type_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-detail_type_id}}',
            '{{%bichuv_nastel_details}}'
        );

        $this->dropTable('{{%bichuv_nastel_details}}');
    }
}
