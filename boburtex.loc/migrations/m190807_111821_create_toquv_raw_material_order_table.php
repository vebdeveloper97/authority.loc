<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_raw_material_order}}`.
 */
class m190807_111821_create_toquv_raw_material_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%toquv_raw_material_order}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(),
            'mato_id' => $this->integer(),
            'comment' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //mato_id
        $this->createIndex(
            'idx-toquv_raw_material_order-mato_id',
            'toquv_raw_material_order',
            'mato_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_order-mato_id',
            'toquv_raw_material_order',
            'mato_id',
            'raw_materials',
            'id'
        );

        //order_id
        $this->createIndex(
            'idx-toquv_raw_material_order-order_id',
            'toquv_raw_material_order',
            'order_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_order-order_id',
            'toquv_raw_material_order',
            'order_id',
            'toquv_orders',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //mato_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_order-mato_id',
            'toquv_raw_material_order'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_order-mato_id',
            'toquv_raw_material_order'
        );

        $this->dropTable('{{%toquv_raw_material_order}}');
    }
}
