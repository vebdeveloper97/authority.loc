<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_goods_doc}}` and `{{%tikuv_goods_doc_items}}`.
 */
class m191031_195001_create_tikuv_goods_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_goods_doc_pack}}', [
            'id' => $this->primaryKey(),
            'doc_number' => $this->string(),
            'reg_date' => $this->dateTime(),
            'department_id' => $this->integer(),
            'order_id' => $this->integer(),
            'order_item_id' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->createTable('{{%tikuv_goods_doc}}', [
            'id' => $this->primaryKey(),
            'tgdp_id' => $this->integer(),
            'barcode' => $this->integer(13),
            'goods_id' => $this->integer(),
            'created_by' => $this->integer(),
            'type' => $this->integer()->defaultValue(1),
            'quantity' => $this->decimal(20,3),
            'model_no' => $this->string(30),
            'model_id' => $this->integer(),
            'size_type' => $this->integer(),
            'size' => $this->integer(),
            'color' => $this->integer(),
            'name' => $this->string(100),
            'old_name' => $this->string(100),
            'category' => $this->integer(),
            'sub_category' => $this->integer(),
            'model_type' => $this->integer(),
            'season' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `tgdp_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc-tgdp_id}}',
            '{{%tikuv_goods_doc}}',
            'tgdp_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc-tgdp_id}}',
            '{{%tikuv_goods_doc}}',
            'tgdp_id',
            '{{%tikuv_goods_doc_pack}}',
            'id'
        );

        // creates index for column `goods_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc-goods_id}}',
            '{{%tikuv_goods_doc}}',
            'goods_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc-goods_id}}',
            '{{%tikuv_goods_doc}}',
            'goods_id',
            '{{%goods}}',
            'id'
        );

        // creates index for column `department_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_pack-department_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'department_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_pack}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_pack-department_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'department_id',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_pack-order_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'order_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_pack}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_pack-order_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'order_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `order_item_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_pack-order_item_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'order_item_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_pack}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_pack-order_item_id}}',
            '{{%tikuv_goods_doc_pack}}',
            'order_item_id',
            '{{%model_orders_items}}',
            'id'
        );

        $this->createTable('{{%tikuv_goods_doc_items}}', [
            'id' => $this->primaryKey(),
            'parent' => $this->integer(),
            'child' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'type' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%goods_id}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc-goods_id}}',
            '{{%tikuv_goods_doc}}'
        );

        // drops index for column `goods_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc-goods_id}}',
            '{{%tikuv_goods_doc}}'
        );
        $this->dropTable('{{%tikuv_goods_doc_items}}');
        $this->dropTable('{{%tikuv_goods_doc}}');
    }
}
