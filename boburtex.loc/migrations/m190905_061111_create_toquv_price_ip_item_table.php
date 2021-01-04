<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_price_ip_item}}`.
 */
class m190905_061111_create_toquv_price_ip_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_price_ip_item}}', [
            'id' => $this->primaryKey(),
            'toquv_price_ip_id' => $this->integer(),
            'toquv_ne_id' => $this->smallInteger(),
            'toquv_thread_id' => $this->smallInteger(),
            'price' => $this->decimal(20, 2),
            'pb_id' => $this->integer()->defaultValue(2),
            'status' => $this->integer()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
        //toquv_price_ip_id
        $this->createIndex(
            'idx-toquv_price_ip_item-toquv_price_ip_id',
            'toquv_price_ip_item',
            'toquv_price_ip_id'
        );

        $this->addForeignKey(
            'fk-toquv_price_ip_item-toquv_price_ip_id',
            'toquv_price_ip_item',
            'toquv_price_ip_id',
            'toquv_price_ip',
            'id'
        );
        // toquv_ne_id
        $this->createIndex(
            'idx-toquv_price_ip_item-toquv_ne_id',
            'toquv_price_ip_item',
            'toquv_ne_id'
        );
        $this->addForeignKey(
            'fk-toquv_price_ip_item-toquv_ne_id',
            'toquv_price_ip_item',
            'toquv_ne_id',
            'toquv_ne',
            'id'
        );
        // toquv_thread_id
        $this->createIndex(
            'idx-toquv_price_ip_item-toquv_thread_id',
            'toquv_price_ip_item',
            'toquv_thread_id'
        );
        $this->addForeignKey(
            'fk-toquv_price_ip_item-thread_id',
            'toquv_price_ip_item',
            'toquv_thread_id',
            'toquv_thread',
            'id'
        );
        //pb_id
        $this->createIndex(
            'idx-toquv_price_ip_item-pb_id',
            'toquv_price_ip_item',
            'pb_id'
        );

        $this->addForeignKey(
            'fk-toquv_price_ip_item-pb_id',
            'toquv_price_ip_item',
            'pb_id',
            'pul_birligi',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //toquv_price_ip_id
        $this->dropForeignKey(
            'fk-toquv_price_ip_item-toquv_price_ip_id',
            'toquv_price_ip_item'
        );

        $this->dropIndex(
            'idx-toquv_price_ip_item-toquv_price_ip_id',
            'toquv_price_ip_item'
        );
        // toquv_ne_id
        $this->dropForeignKey(
            'idx-toquv_price_ip_item-toquv_ne_id',
            'toquv_price_ip_item'
        );
        $this->dropIndex(
            'idx-toquv_price_ip_item-toquv_ne_id',
            'toquv_price_ip_item'
        );
        // toquv_thread_id
        $this->dropForeignKey(
            'idx-toquv_price_ip_item-toquv_thread_id',
            'toquv_price_ip_item'
        );
        $this->dropIndex(
            'idx-toquv_price_ip_item-toquv_thread_id',
            'toquv_price_ip_item'
        );
        //pb_id
        $this->dropForeignKey(
            'idx-toquv_price_ip_item-pb_id',
            'toquv_price_ip_item'
        );

        $this->dropIndex(
            'idx-toquv_price_ip_item-pb_id',
            'toquv_price_ip_item'
        );
        $this->dropTable('{{%toquv_price_ip_item}}');
    }
}
