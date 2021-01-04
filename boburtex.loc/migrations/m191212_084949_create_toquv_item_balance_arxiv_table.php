<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_item_balance_arxiv}}`.
 */
class m191212_084949_create_toquv_item_balance_arxiv_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_item_balance_arxiv}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            // 1 - Ip, 2 - Mato, 3-kraska, 4 - etc
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'count' => $this->decimal(20, 3)->notNull(),
            'inventory' => $this->decimal(20, 3)->notNull(),
            'reg_date' => $this->dateTime()->defaultExpression('NOW()')
                    ->append('ON UPDATE NOW()'),
            'department_id' => $this->integer(),
            //1 -Bizniki, 2- uniki
            'is_own' => $this->smallInteger()->defaultValue(1),
            'price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sum_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sum_usd' => $this->decimal(20,2)->defaultValue(0),
            'document_id' => $this->integer(),
            'document_type' => $this->smallInteger()->defaultValue(1),
            'version' => $this->smallInteger()->defaultValue(0),
            'comment' => $this->text(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'price_rub' => $this->decimal(20,2),
            'sold_price_rub' => $this->decimal(20,2),
            'price_eur' => $this->decimal(20,2),
            'sold_price_eur' => $this->decimal(20,2),
            'lot' => $this->string(),
            'to_department' => $this->integer(),
            'musteri_id' => $this->bigInteger(20)
        ]);
        //department_id
        $this->createIndex(
            'idx-toquv_item_balance_arxiv-department_id',
            'toquv_item_balance_arxiv',
            'department_id'
        );
        //to_department
        $this->createIndex(
            'idx-toquv_item_balance_arxiv-to_department',
            'toquv_item_balance_arxiv',
            'to_department'
        );
        //musteri_id
        $this->createIndex(
            'idx-toquv_item_balance_arxiv-musteri_id',
            'toquv_item_balance_arxiv',
            'musteri_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-toquv_item_balance_arxiv-department_id',
            'toquv_item_balance_arxiv'
        );
        $this->dropIndex(
            'idx-toquv_item_balance_arxiv-to_department',
            'toquv_item_balance_arxiv'
        );
        $this->dropIndex(
            'idx-toquv_item_balance_arxiv-musteri_id',
            'toquv_item_balance_arxiv'
        );
        $this->dropTable('{{%toquv_item_balance_arxiv}}');
    }
}
