<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_package_item_balance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%goods}}`
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%sort_name}}`
 */
class m200323_083635_create_tikuv_package_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_package_item_balance}}', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer(),
            'count' => $this->integer()->defaultValue(0),
            'inventory' => $this->integer()->defaultValue(0),
            'nastel_no' => $this->string(25),
            'doc_type' => $this->smallInteger(2)->defaultValue(1),
            'dept_type' => $this->string(2)->defaultValue('P'),
            'department_id' => $this->integer(),
            'model_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'sort_type_id' => $this->integer(),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `goods_id`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-goods_id}}',
            '{{%tikuv_package_item_balance}}',
            'goods_id'
        );

        // add foreign key for table `{{%goods}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-goods_id}}',
            '{{%tikuv_package_item_balance}}',
            'goods_id',
            '{{%goods}}',
            'id'
        );

        // creates index for column `model_list_id`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-model_list_id}}',
            '{{%tikuv_package_item_balance}}',
            'model_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-model_list_id}}',
            '{{%tikuv_package_item_balance}}',
            'model_list_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-model_var_id}}',
            '{{%tikuv_package_item_balance}}',
            'model_var_id'
        );

        // add foreign key for table `{{%model_var}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-model_var_id}}',
            '{{%tikuv_package_item_balance}}',
            'model_var_id',
            '{{%models_variations}}',
            'id'
        );

        // creates index for column `sort_type_id`
        $this->createIndex(
            '{{%idx-tikuv_package_item_balance-sort_type_id}}',
            '{{%tikuv_package_item_balance}}',
            'sort_type_id'
        );

        // add foreign key for table `{{%sort_type}}`
        $this->addForeignKey(
            '{{%fk-tikuv_package_item_balance-sort_type_id}}',
            '{{%tikuv_package_item_balance}}',
            'sort_type_id',
            '{{%sort_name}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%goods}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-goods_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `goods_id`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-goods_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops foreign key for table `{{%model_list}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-model_list_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `model_list_id`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-model_list_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops foreign key for table `{{%model_var}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-model_var_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-model_var_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops foreign key for table `{{%sort_type}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_package_item_balance-sort_type_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        // drops index for column `sort_type_id`
        $this->dropIndex(
            '{{%idx-tikuv_package_item_balance-sort_type_id}}',
            '{{%tikuv_package_item_balance}}'
        );

        $this->dropTable('{{%tikuv_package_item_balance}}');
    }
}
