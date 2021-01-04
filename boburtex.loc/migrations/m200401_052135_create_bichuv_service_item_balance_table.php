<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_service_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 */
class m200401_052135_create_bichuv_service_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_service_item_balance}}', [
            'id' => $this->primaryKey(),
            'musteri_id' => $this->bigInteger(),
            'size_id' => $this->integer(),
            'sort_id' => $this->integer(),
            'nastel_no' => $this->string(50),
            'department_id' => $this->integer(),
            'count' => $this->integer()->defaultValue(0),
            'inventory' => $this->integer()->defaultValue(0),
            'doc_type' => $this->smallInteger(2)->defaultValue(1),
            'model_id' => $this->integer(),
            'model_var' => $this->integer(),
            'doc_id' => $this->integer(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-bichuv_service_item_balance-nastel_no}}',
            '{{%bichuv_service_item_balance}}',
            'nastel_no'
        );

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-bichuv_service_item_balance-musteri_id}}',
            '{{%bichuv_service_item_balance}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-bichuv_service_item_balance-musteri_id}}',
            '{{%bichuv_service_item_balance}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-bichuv_service_item_balance-size_id}}',
            '{{%bichuv_service_item_balance}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-bichuv_service_item_balance-size_id}}',
            '{{%bichuv_service_item_balance}}',
            'size_id',
            '{{%size}}',
            'id'
        );

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-bichuv_service_item_balance-department_id}}',
            '{{%bichuv_service_item_balance}}',
            'department_id'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-bichuv_service_item_balance-department_id}}',
            '{{%bichuv_service_item_balance}}',
            'department_id',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-bichuv_service_item_balance-model_id}}',
            '{{%bichuv_service_item_balance}}',
            'model_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-bichuv_service_item_balance-model_id}}',
            '{{%bichuv_service_item_balance}}',
            'model_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `sort_id`
        $this->createIndex(
            '{{%idx-bichuv_service_item_balance-sort_id}}',
            '{{%bichuv_service_item_balance}}',
            'sort_id'
        );

        // add foreign key for table `{{%sort_name}}`
        $this->addForeignKey(
            '{{%fk-bichuv_service_item_balance-sort_id}}',
            '{{%bichuv_service_item_balance}}',
            'sort_id',
            '{{%sort_name}}',
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
            '{{%idx-bichuv_service_item_balance-nastel_no}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_service_item_balance-musteri_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-bichuv_service_item_balance-musteri_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_service_item_balance-size_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-bichuv_service_item_balance-size_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_service_item_balance-department_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops index for column `department_id`
        $this->dropIndex(
            '{{%idx-bichuv_service_item_balance-department_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_service_item_balance-model_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-bichuv_service_item_balance-model_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops foreign key for table `{{%sort_name}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_service_item_balance-sort_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        // drops index for column `sort_id`
        $this->dropIndex(
            '{{%idx-bichuv_service_item_balance-sort_id}}',
            '{{%bichuv_service_item_balance}}'
        );

        $this->dropTable('{{%bichuv_service_item_balance}}');
    }
}
