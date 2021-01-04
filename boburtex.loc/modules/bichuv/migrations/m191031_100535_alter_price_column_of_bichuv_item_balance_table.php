<?php

use yii\db\Migration;

/**
 * Class m191031_100535_alter_price_column_of_bichuv_item_balance_table
 */
class m191031_100535_alter_price_column_of_bichuv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bichuv_item_balance', 'price_uzs', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'price_usd', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'price_rub', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'price_eur', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_uzs', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_usd', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_rub', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_eur', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_uzs', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_usd', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_rub', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_eur', $this->decimal(20,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('bichuv_item_balance', 'price_uzs', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'price_usd', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'price_rub', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'price_eur', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_uzs', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_usd', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_rub', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sold_price_eur', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_uzs', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_usd', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_rub', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_item_balance', 'sum_eur', $this->decimal(20,2)->defaultValue(0));
    }
}
