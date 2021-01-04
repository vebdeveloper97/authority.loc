<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_rm_order_items}}`.
 */
class m190807_113128_create_toquv_rm_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_rm_order_items}}', [
            'id' => $this->primaryKey(),
            'ip_id' => $this->integer(),
            'percentage' => $this->integer()->defaultValue(100),
            'own_quantity' => $this->decimal(20,3)->defaultValue(0),
            'their_quantity' => $this->decimal(20,3)->defaultValue(0),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

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

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
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

        $this->dropTable('{{%toquv_rm_order_items}}');
    }
}
