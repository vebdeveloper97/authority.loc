<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_detail_items}}`.
 */
class m200217_115946_create_bichuv_nastel_detail_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_detail_items}}', [
            'id' => $this->primaryKey(),
            'size_id' => $this->integer(),
            'bichuv_nastel_detail_id' => $this->integer(),
            'count' => $this->integer(6),
            'required_count' => $this->integer(6),
            'weight' => $this->decimal(20,3),
            'required_weight' => $this->decimal(20,3),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_detail_items-size_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_detail_items-size_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'size_id',
            '{{%size}}',
            'id'
        );

        // creates index for column `bichuv_nastel_detail_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_nastel_detail_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_nastel_detail_id'
        );

        // add foreign key for table `{{%bichuv_nastel_details}}`
        $this->addForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_nastel_detail_id}}',
            '{{%bichuv_nastel_detail_items}}',
            'bichuv_nastel_detail_id',
            '{{%bichuv_nastel_details}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_detail_items-size_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_detail_items-size_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        // drops foreign key for table `{{%bichuv_nastel_details}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_detail_items-bichuv_nastel_detail_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        // drops index for column `bichuv_nastel_detail_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_detail_items-bichuv_nastel_detail_id}}',
            '{{%bichuv_nastel_detail_items}}'
        );

        $this->dropTable('{{%bichuv_nastel_detail_items}}');
    }
}
