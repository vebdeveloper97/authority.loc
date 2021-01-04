<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_slice_item_balance}}`.
 */
class m191228_172536_create_bichuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_slice_item_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(2)->defaultValue(1),
            'party_no' => $this->string(20),
            'size_id' => $this->integer(),
            'count' => $this->decimal(20,3),
            'inventory' => $this->decimal(20,3),
            'doc_id' => $this->integer(),
            'doc_type' => $this->smallInteger(1)->defaultValue(1),
            'department_id' => $this->integer(),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'updated_at' => $this->integer(),
            'created_at' => $this->integer()
        ]);

        // creates index for column `party_no`
        $this->createIndex(
            '{{%idx-bichuv_slice_item_balance-party_no}}',
            '{{%bichuv_slice_item_balance}}',
            'party_no'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_item_balance-size_id}}',
            '{{%bichuv_slice_item_balance}}',
            'size_id'
        );



        // add foreign key for table `{{%size_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_item_balance-size_id}}',
            '{{%bichuv_slice_item_balance}}',
            'size_id',
            '{{%size}}',
            'id'
        );

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-bichuv_slice_item_balance-department_id}}',
            '{{%bichuv_slice_item_balance}}',
            'department_id'
        );

        // add foreign key for table `{{%department_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_item_balance-department_id}}',
            '{{%bichuv_slice_item_balance}}',
            'department_id',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-bichuv_slice_item_balance-from_department}}',
            '{{%bichuv_slice_item_balance}}',
            'from_department'
        );

        // add foreign key for table `{{%from_department}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_item_balance-from_department}}',
            '{{%bichuv_slice_item_balance}}',
            'from_department',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-bichuv_slice_item_balance-to_department}}',
            '{{%bichuv_slice_item_balance}}',
            'to_department'
        );

        // add foreign key for table `{{%to_department}}`
        $this->addForeignKey(
            '{{%fk-bichuv_slice_item_balance-to_department}}',
            '{{%bichuv_slice_item_balance}}',
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
        // drops index for column `party_no`
        $this->dropIndex(
            '{{%idx-bichuv_slice_item_balance-party_no}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%size_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_item_balance-size_id}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_item_balance-size_id}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%department_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_item_balance-department_id}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-bichuv_slice_item_balance-department_id}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%from_department}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_item_balance-from_department}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-bichuv_slice_item_balance-from_department}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops foreign key for table `{{%to_department}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_slice_item_balance-to_department}}',
            '{{%bichuv_slice_item_balance}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-bichuv_slice_item_balance-to_department}}',
            '{{%bichuv_slice_item_balance}}'
        );

        $this->dropTable('{{%bichuv_slice_item_balance}}');
    }
}
