<?php

use yii\db\Migration;

/**
 * Class m190906_060815_add_some_columns_drop_ip_id_toquv_rm_order_items_table
 */
class m190906_060815_add_some_columns_drop_ip_id_toquv_rm_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //ip_id
        $this->dropForeignKey(
            'fk-toquv_rm_order_items-ip_id',
            'toquv_rm_order_items'
        );

        $this->dropIndex(
            'idx-toquv_rm_order_items-ip_id',
            'toquv_rm_order_items'
        );
        $this->dropColumn('toquv_rm_order_items', 'ip_id');

        //toquv_rm_order_id
        $this->addColumn('toquv_rm_order_items','toquv_rm_order_id',$this->integer());
        $this->createIndex(
            'idx-toquv_rm_order_items-toquv_rm_order_id',
            'toquv_rm_order_items',
            'toquv_rm_order_id'
        );

        $this->addForeignKey(
            'fk-toquv_rm_order_items-toquv_rm_order_id',
            'toquv_rm_order_items',
            'toquv_rm_order_id',
            'toquv_rm_order',
            'id',
            'CASCADE',
            'CASCADE'
        );
        //toquv_ne_id
        $this->addColumn('toquv_rm_order_items','toquv_ne_id',$this->smallInteger());
        $this->createIndex(
            'idx-toquv_rm_order_items-toquv_ne_id',
            'toquv_rm_order_items',
            'toquv_ne_id'
        );

        $this->addForeignKey(
            'fk-toquv_rm_order_items-toquv_ne_id',
            'toquv_rm_order_items',
            'toquv_ne_id',
            'toquv_ne',
            'id'
        );
        //toquv_thread_id
        $this->addColumn('toquv_rm_order_items','toquv_thread_id',$this->smallInteger());
        $this->createIndex(
            'idx-toquv_rm_order_items-toquv_thread_id',
            'toquv_rm_order_items',
            'toquv_thread_id'
        );

        $this->addForeignKey(
            'fk-toquv_rm_order_items-toquv_thread_id',
            'toquv_rm_order_items',
            'toquv_thread_id',
            'toquv_thread',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('toquv_rm_order_items','ip_id',$this->integer());
        //ip_id
        $this->createIndex(
            'idx-toquv_rm_order_items-ip_id',
            'toquv_rm_order_items',
            'ip_id'
        );

        $this->addForeignKey(
            'fk-toquv_rm_order_items-ip_id',
            'toquv_rm_order_items',
            'ip_id',
            'toquv_ip',
            'id'
        );
        //toquv_rm_order_id
        $this->dropForeignKey(
            'fk-toquv_rm_order_items-toquv_rm_order_id',
            'toquv_rm_order_items'
        );

        $this->dropIndex(
            'idx-toquv_rm_order_items-toquv_rm_order_id',
            'toquv_rm_order_items'
        );
        $this->dropColumn('toquv_rm_order_items', 'toquv_rm_order_id');
        //toquv_ne_id
        $this->dropForeignKey(
            'fk-toquv_rm_order_items-toquv_ne_id',
            'toquv_rm_order_items'
        );

        $this->dropIndex(
            'idx-toquv_rm_order_items-toquv_ne_id',
            'toquv_rm_order_items'
        );
        $this->dropColumn('toquv_rm_order_items', 'toquv_ne_id');
        //toquv_thread_id
        $this->dropForeignKey(
            'fk-toquv_rm_order_items-toquv_thread_id',
            'toquv_rm_order_items'
        );

        $this->dropIndex(
            'idx-toquv_rm_order_items-toquv_thread_id',
            'toquv_rm_order_items'
        );
        $this->dropColumn('toquv_rm_order_items', 'toquv_thread_id');
    }
}
