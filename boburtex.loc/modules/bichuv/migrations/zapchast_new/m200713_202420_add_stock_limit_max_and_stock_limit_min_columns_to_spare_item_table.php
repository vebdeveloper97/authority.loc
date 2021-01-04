<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item}}`.
 */
class m200713_202420_add_stock_limit_max_and_stock_limit_min_columns_to_spare_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item}}', 'stock_limit_max', $this->decimal(20,3));
        $this->addColumn('{{%spare_item}}', 'stock_limit_min', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item}}', 'stock_limit_max');
        $this->dropColumn('{{%spare_item}}', 'stock_limit_min');
    }
}
