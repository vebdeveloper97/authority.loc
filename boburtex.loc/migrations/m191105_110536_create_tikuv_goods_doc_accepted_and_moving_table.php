<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_goods_doc}}`.
 * Has foreign keys to the tables:
 *
 */
class m191105_110536_create_tikuv_goods_doc_accepted_and_moving_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_goods_doc_accepted}}', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer(),
            'order_id' => $this->integer(),
            'order_item_id' => $this->integer(),
            'quantity' => $this->decimal(20,3)->defaultValue(0),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createTable('{{%tikuv_goods_doc_moving}}', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer(),
            'order_id' => $this->integer(),
            'order_item_id' => $this->integer(),
            'quantity' => $this->decimal(20,3)->defaultValue(0),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `tikuv_goods_doc_accepted goods_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_accepted-goods_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'goods_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_accepted goods_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-goods_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'goods_id',
            '{{%goods}}',
            'id'
        );


        // creates index for column `tikuv_goods_doc_accepted order_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_accepted-order_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'order_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_accepted order_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-order_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'order_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `tikuv_goods_doc_accepted order_item_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_accepted-order_item_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'order_item_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_accepted order_item_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-order_item_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'order_item_id',
            '{{%model_orders_items}}',
            'id'
        );


        // creates index for column `tikuv_goods_doc_moving`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_moving-goods_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'goods_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_moving}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_moving-goods_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'goods_id',
            '{{%goods}}',
            'id'
        );

        // creates index for column `tikuv_goods_doc_moving order_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_moving-order_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'order_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_moving order_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_moving-order_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'order_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `tikuv_goods_doc_moving order_item_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_moving-order_item_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'order_item_id'
        );

        // add foreign key for table `{{%tikuv_goods_doc_moving order_item_id}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_moving-order_item_id}}',
            '{{%tikuv_goods_doc_moving}}',
            'order_item_id',
            '{{%model_orders_items}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tikuv_goods_doc_accepted}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-goods_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops index for column `tikuv_goods_doc_accepted`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_accepted-goods_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops foreign key for table `{{%tikuv_goods_doc_accepted}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-order_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops index for column `tikuv_goods_doc_accepted`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_accepted-order_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops foreign key for table `{{%tikuv_goods_doc_accepted}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-order_item_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops index for column `tikuv_goods_doc_accepted`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_accepted-order_item_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );


        // drops foreign key for table `{{%tikuv_goods_doc_moving}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_moving-goods_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops index for column `tikuv_goods_doc_moving`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_moving-goods_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops foreign key for table `{{%tikuv_goods_doc_moving}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_moving-order_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops index for column `tikuv_goods_doc_moving`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_moving-order_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops foreign key for table `{{%tikuv_goods_doc_moving}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_moving-order_item_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        // drops index for column `tikuv_goods_doc_moving`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_moving-order_item_id}}',
            '{{%tikuv_goods_doc_moving}}'
        );

        $this->dropTable('{{%tikuv_goods_doc_accepted}}');
        $this->dropTable('{{%tikuv_goods_doc_moving}}');
    }
}
