<?php

use yii\db\Migration;

/**
 * Class m190807_053819_add_some_fields_to_item_balance_table
 */
class m190807_053819_add_some_fields_to_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_item_balance','price_rub', $this->decimal(20,2));
        $this->addColumn('toquv_item_balance','sold_price_rub', $this->decimal(20,2));
        $this->addColumn('toquv_item_balance','price_eur', $this->decimal(20,2));
        $this->addColumn('toquv_item_balance','sold_price_eur', $this->decimal(20,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('toquv_item_balance','price_rub');
        $this->dropColumn('toquv_item_balance','sold_price_rub');
        $this->dropColumn('toquv_item_balance','price_eur');
        $this->dropColumn('toquv_item_balance','sold_price_eur');
    }
}
