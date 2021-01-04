<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_service_pricing}}`.
 */
class m190807_073325_create_toquv_service_pricing_table extends Migration
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
        $this->createTable('{{%toquv_service_pricing}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'service_type' => $this->smallInteger()->defaultValue(1),
            'ip_id' => $this->integer(),
            'mato_id' => $this->integer(),
            'is_sale' => $this->boolean()->defaultValue(1),
            'price_usd' => $this->decimal(20,2),
            'price_uzs' => $this->decimal(20,2),
            'price_rub' => $this->decimal(20,2),
            'price_eur' => $this->decimal(20,2),
            'comment' => $this->text(),
            'unit_id' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //ip_id
        $this->createIndex(
            'idx-toquv_service_pricing-ip_id',
            'toquv_service_pricing',
            'ip_id'
        );

        $this->addForeignKey(
            'fk-toquv_service_pricing-ip_id',
            'toquv_service_pricing',
            'ip_id',
            'toquv_ip',
            'id'
        );

        //mato_id
        $this->createIndex(
            'idx-toquv_service_pricing-mato_id',
            'toquv_service_pricing',
            'mato_id'
        );

        $this->addForeignKey(
            'fk-toquv_service_pricing-mato_id',
            'toquv_service_pricing',
            'mato_id',
            'toquv_raw_materials',
            'id'
        );

        //unit_id
        $this->createIndex(
            'idx-toquv_service_pricing-unit_id',
            'toquv_service_pricing',
            'unit_id'
        );

        $this->addForeignKey(
            'fk-toquv_service_pricing-unit_id',
            'toquv_service_pricing',
            'unit_id',
            'unit',
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
            'fk-toquv_service_pricing-ip_id',
            'toquv_service_pricing'
        );

        $this->dropIndex(
            'idx-toquv_service_pricing-ip_id',
            'toquv_service_pricing'
        );

        //mato_id
        $this->dropForeignKey(
            'fk-toquv_service_pricing-mato_id',
            'toquv_service_pricing'
        );

        $this->dropIndex(
            'idx-toquv_service_pricing-mato_id',
            'toquv_service_pricing'
        );

        //ip_id
        $this->dropForeignKey(
            'fk-toquv_service_pricing-unit_id',
            'toquv_service_pricing'
        );

        $this->dropIndex(
            'idx-toquv_service_pricing-unit_id',
            'toquv_service_pricing'
        );

        $this->dropTable('{{%toquv_service_pricing}}');
    }
}
