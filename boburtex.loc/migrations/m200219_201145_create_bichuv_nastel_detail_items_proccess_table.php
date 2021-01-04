<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_detail_items_proccess}}`.
 */
class m200219_201145_create_bichuv_nastel_detail_items_proccess_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_detail_items_proccess}}', [
            'id' => $this->primaryKey(),
            'bichuv_nastel_detail_items_id' => $this->integer(),
            'count' => $this->integer(),
            'weight' => $this->decimal(20,3),
            'type' => $this->smallInteger(1),
            'status' => $this->smallInteger(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `bichuv_nastel_detail_items_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_detail_items_proccess}}',
            'bichuv_nastel_detail_items_id'
        );
        // add foreign key for table `{{%bichuv_nastel_detail_items_proccess}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_detail_items_proccess}}',
            'bichuv_nastel_detail_items_id',
            '{{%bichuv_nastel_detail_items}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        // drops foreign key for table `{{%size_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_detail_items_proccess}}'
        );

        // drops index for column `nastel_no`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_nastel_detail_items_id}}',
            '{{%bichuv_nastel_detail_items_proccess}}'
        );

        $this->dropTable('{{%bichuv_nastel_detail_items_proccess}}');
    }
}
