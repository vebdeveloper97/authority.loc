<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_orders}}`.
 */
class m190807_090543_create_toquv_orders_table extends Migration
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
        $this->createTable('{{%toquv_orders}}', [
            'id' => $this->primaryKey(),
            'musteri_id' => $this->bigInteger(),
            'order_number' => $this->integer(),
            'document_number' => $this->integer(),
            'reg_date' => $this->dateTime(),
            'responsible_persons' => $this->text(),
            'comment' => $this->text(),
            'sum_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sum_usd' => $this->decimal(20,2)->defaultValue(0),
            'sum_rub' => $this->decimal(20,2)->defaultValue(0),
            'sum_eur' => $this->decimal(20,2)->defaultValue(0),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //musteri_id
        $this->createIndex(
            'idx-toquv_orders-musteri_id',
            'toquv_orders',
            'musteri_id'
        );

        $this->addForeignKey(
            'fk-toquv_orders-musteri_id',
            'toquv_orders',
            'musteri_id',
            'musteri',
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
            'fk-toquv_orders-musteri_id',
            'toquv_orders'
        );

        $this->dropIndex(
            'idx-toquv_orders-musteri_id',
            'toquv_orders'
        );

        $this->dropTable('{{%toquv_orders}}');
    }
}
