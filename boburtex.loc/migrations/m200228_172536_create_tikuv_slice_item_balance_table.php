<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_slice_item_balance}}`.
 */
class m200228_172536_create_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_slice_item_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(2)->defaultValue(1),
            'size_id' => $this->integer(),
            'nastel_no' => $this->string(20),
            'count' => $this->decimal(20,3),
            'inventory' => $this->decimal(20,3),
            'doc_id' => $this->integer(),
            'doc_type' => $this->smallInteger(1)->defaultValue(1),
            'department_id' => $this->integer(),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'model_id' => $this->smallInteger(6),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer()
        ]);

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-nastel_no}}',
            '{{%tikuv_slice_item_balance}}',
            'nastel_no'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-size_id}}',
            '{{%tikuv_slice_item_balance}}',
            'size_id'
        );
        // add foreign key for table `{{%size_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-size_id}}',
            '{{%tikuv_slice_item_balance}}',
            'size_id',
            '{{%size}}',
            'id'
        );

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}',
            'model_id'
        );
        // add foreign key for table `{{%model_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}',
            'model_id',
            '{{%product}}',
            'id'
        );

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-department_id}}',
            '{{%tikuv_slice_item_balance}}',
            'department_id'
        );

        // add foreign key for table `{{%department_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-department_id}}',
            '{{%tikuv_slice_item_balance}}',
            'department_id',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-from_department}}',
            '{{%tikuv_slice_item_balance}}',
            'from_department'
        );

        // add foreign key for table `{{%from_department}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-from_department}}',
            '{{%tikuv_slice_item_balance}}',
            'from_department',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-tikuv_slice_item_balance-to_department}}',
            '{{%tikuv_slice_item_balance}}',
            'to_department'
        );

        // add foreign key for table `{{%to_department}}`
        $this->addForeignKey(
            '{{%fk-tikuv_slice_item_balance-to_department}}',
            '{{%tikuv_slice_item_balance}}',
            'to_department',
            '{{%toquv_departments}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `nastel_no`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-nastel_no}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%size_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-size_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-size_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%department_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-department_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-department_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%from_department}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-from_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-from_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%to_department}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-to_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-to_department}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%model_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-tikuv_slice_item_balance-model_id}}',
            '{{%tikuv_slice_item_balance}}'
        );

        $this->dropTable('{{%tikuv_slice_item_balance}}');
    }
}
