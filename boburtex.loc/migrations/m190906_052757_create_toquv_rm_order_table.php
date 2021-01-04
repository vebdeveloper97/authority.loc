<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_rm_order}}`.
 */
class m190906_052757_create_toquv_rm_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_rm_order}}', [
            'id' => $this->primaryKey(),
            'toquv_orders_id' => $this->integer(),
            'toquv_raw_materials_id' => $this->integer(),
            'priority' => $this->smallInteger(2)->defaultValue(1),
            'rm_type' => $this->smallInteger(2)->defaultValue(1),
            'price' => $this->decimal(20, 2),
            'price_fakt' => $this->decimal(20, 2),
            'pb_id' => $this->integer()->defaultValue(2),
            'discount' => $this->integer(),
            'percentage' => $this->decimal(3, 2),
            'quantity' => $this->decimal(20, 3),
            'unit_id' => $this->integer()->defaultValue(2),
            'done_date' => 'datetime DEFAULT NOW()',
            'status' => $this->integer()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
        //toquv_orders_id
        $this->createIndex(
            'idx-toquv_rm_order-toquv_orders_id',
            '{{%toquv_rm_order}}',
            'toquv_orders_id'
        );

        $this->addForeignKey(
            'fk-toquv_rm_order-toquv_orders_id',
            '{{%toquv_rm_order}}',
            'toquv_orders_id',
            '{{%toquv_orders}}',
            'id'
        );
        // toquv_raw_materials_id
        $this->createIndex(
            'idx-toquv_rm_order-toquv_raw_materials_id',
            '{{%toquv_rm_order}}',
            'toquv_raw_materials_id'
        );
        $this->addForeignKey(
            'fk-toquv_rm_order-toquv_raw_materials_id',
            '{{%toquv_rm_order}}',
            'toquv_raw_materials_id',
            '{{%toquv_raw_materials}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //toquv_orders_id
        $this->dropForeignKey(
            'fk-toquv_rm_order-toquv_orders_id',
            '{{%toquv_rm_order}}'
        );

        $this->dropIndex(
            'idx-toquv_rm_order-toquv_orders_id',
            '{{%toquv_rm_order}}'
        );
        //toquv_raw_materials_id
        $this->dropForeignKey(
            'fk-toquv_rm_order-toquv_raw_materials_id',
            '{{%toquv_rm_order}}'
        );

        $this->dropIndex(
            'idx-toquv_rm_order-toquv_raw_materials_id',
            '{{%toquv_rm_order}}'
        );
        $this->dropTable('{{%toquv_rm_order}}');
    }
}
