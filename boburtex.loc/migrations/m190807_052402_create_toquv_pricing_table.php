<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_pricing}}`.
 */
class m190807_052402_create_toquv_pricing_table extends Migration
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
        $this->createTable('{{%toquv_pricing}}', [
            'id' => $this->primaryKey(),
            'ip_id' => $this->integer(),
            'mato_id' => $this->integer(),

            'incoming_price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'incoming_price_usd' => $this->decimal(20,2)->defaultValue(0),
            'incoming_price_rub' => $this->decimal(20,2)->defaultValue(0),
            'incoming_price_eur' => $this->decimal(20,2)->defaultValue(0),

            'sold_price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_rub' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_eur' => $this->decimal(20,2)->defaultValue(0),

            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'comment' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //ip_id
        $this->createIndex(
            'idx-toquv_pricing-ip_id',
            'toquv_pricing',
            'ip_id'
        );

        $this->addForeignKey(
            'fk-toquv_pricing-ip_id',
            'toquv_pricing',
            'ip_id',
            'toquv_ip',
            'id'
        );

        //mato_id
        $this->createIndex(
            'idx-toquv_pricing-mato_id',
            'toquv_pricing',
            'mato_id'
        );

        $this->addForeignKey(
            'fk-toquv_pricing-mato_id',
            'toquv_pricing',
            'mato_id',
            'raw_materials',
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
            'fk-toquv_pricing-ip_id',
            'toquv_pricing'
        );

        $this->dropIndex(
            'idx-toquv_pricing-ip_id',
            'toquv_pricing'
        );

        //mato_id
        $this->dropForeignKey(
            'fk-toquv_pricing-mato_id',
            'toquv_pricing'
        );

        $this->dropIndex(
            'idx-toquv_pricing-mato_id',
            'toquv_pricing'
        );

        $this->dropTable('{{%toquv_pricing}}');
    }
}
