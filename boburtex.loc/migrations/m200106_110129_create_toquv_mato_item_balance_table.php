<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_mato_item_balance}}`.
 */
class m200106_110129_create_toquv_mato_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_mato_item_balance}}', [
            'id' => $this->primaryKey(),
            'tir_id' => $this->integer(),
            'trm_id' => $this->integer(),
            'entity_id' => $this->integer(),
            // 1 - Ip, 2 - Mato, 3-kraska, 4 - etc
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'count' => $this->decimal(20, 3)->notNull(),
            'inventory' => $this->decimal(20, 3)->notNull(),
            'roll_count' => $this->decimal(20,3)->defaultValue(0),
            'roll_inventory' => $this->decimal(20,3)->defaultValue(0),
            'quantity_count' => $this->decimal(20,3)->defaultValue(0),
            'quantity_inventory' => $this->decimal(20,3)->defaultValue(0),
            'reg_date' => $this->dateTime()->defaultExpression('NOW()')
                ->append('ON UPDATE NOW()'),
            'department_id' => $this->integer(),
            'to_department' => $this->integer(),
            'from_department' => $this->integer(),
            'lot' => $this->string(),
            'musteri_id' => $this->bigInteger(20),
            'to_musteri' => $this->bigInteger(20),
            'from_musteri' => $this->bigInteger(20),
            //1 -Bizniki, 2- uniki
            'is_own' => $this->smallInteger()->defaultValue(1),
            'price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sum_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sum_usd' => $this->decimal(20,2)->defaultValue(0),
            'price_rub' => $this->decimal(20,2),
            'sold_price_rub' => $this->decimal(20,2),
            'price_eur' => $this->decimal(20,2),
            'sold_price_eur' => $this->decimal(20,2),
            'document_id' => $this->integer(),
            'document_type' => $this->smallInteger()->defaultValue(1),
            'version' => $this->smallInteger()->defaultValue(0),
            'comment' => $this->text(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'parent_id' => $this->integer()->defaultValue(0)
        ]);
        //entity_id
        $this->createIndex(
            'idx-toquv_mato_item_balance-entity_id',
            'toquv_mato_item_balance',
            'entity_id'
        );
        //department_id
        $this->createIndex(
            'idx-toquv_mato_item_balance-department_id',
            'toquv_mato_item_balance',
            'department_id'
        );
        //to_department
        $this->createIndex(
            'idx-toquv_mato_item_balance-to_department',
            'toquv_mato_item_balance',
            'to_department'
        );
        //from_department
        $this->createIndex(
            'idx-toquv_mato_item_balance-from_department',
            'toquv_mato_item_balance',
            'from_department'
        );
        //musteri_id
        $this->createIndex(
            'idx-toquv_mato_item_balance-musteri_id',
            'toquv_mato_item_balance',
            'musteri_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-toquv_mato_item_balance-entity_id',
            'toquv_mato_item_balance'
        );
        $this->dropIndex(
            'idx-toquv_mato_item_balance-department_id',
            'toquv_mato_item_balance'
        );
        $this->dropIndex(
            'idx-toquv_mato_item_balance-to_department',
            'toquv_mato_item_balance'
        );
        $this->dropIndex(
            'idx-toquv_mato_item_balance-from_department',
            'toquv_mato_item_balance'
        );
        $this->dropIndex(
            'idx-toquv_mato_item_balance-musteri_id',
            'toquv_mato_item_balance'
        );
        $this->dropTable('{{%toquv_mato_item_balance}}');
    }
}
