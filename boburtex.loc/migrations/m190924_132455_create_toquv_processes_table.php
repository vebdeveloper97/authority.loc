<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_makine_processes}}`.
 */
class m190924_132455_create_toquv_processes_table extends Migration
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
        $this->createTable('{{%toquv_makine_processes}}', [
            'id' => $this->primaryKey(),
            'toquv_order_id' => $this->integer(),
            'toquv_order_item_id' => $this->integer(),
            'machine_id' => $this->integer(),
            'user_id' => $this->integer(),
            'started_at' => $this->dateTime(),
            'ended_at' => $this->dateTime(),
            'created_by' => $this->integer()
        ], $tableOptions);

        //musteri_id
        $this->createIndex(
            'idx-toquv_makine-toquv_order_id',
            'toquv_makine_processes',
            'toquv_order_id'
        );

        $this->addForeignKey(
            'fk-toquv_toquv_makine-toquv_order_id',
            'toquv_makine_processes',
            'toquv_order_id',
            'toquv_orders',
            'id'
        );
        $this->createIndex(
            'idx-toquv_makine-toquv_order_item_id',
            'toquv_makine_processes',
            'toquv_order_item_id'
        );

        $this->addForeignKey(
            'fk-toquv_toquv_makine-toquv_order_item_id',
            'toquv_makine_processes',
            'toquv_order_item_id',
            'toquv_rm_order',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //musteri_id
        $this->dropForeignKey(
            'fk-toquv_toquv_makine-toquv_order_id',
            'toquv_makine_processes'
        );

        $this->dropIndex(
            'idx-toquv_makine-toquv_order_id',
            'toquv_makine_processes'
        );
        $this->dropForeignKey(
            'fk-toquv_toquv_makine-toquv_order_item_id',
            'toquv_makine_processes'
        );

        $this->dropIndex(
            'idx-toquv_makine-toquv_order_item_id',
            'toquv_makine_processes'
        );

        $this->dropTable('{{%toquv_makine_processes}}');
    }
}
