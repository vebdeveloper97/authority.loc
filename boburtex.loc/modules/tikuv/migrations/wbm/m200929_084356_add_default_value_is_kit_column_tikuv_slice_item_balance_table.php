<?php

use yii\db\Migration;

/**
 * Class m200929_084356_add_default_value_is_kit_column_tikuv_slice_item_balance_table
 */
class m200929_084356_add_default_value_is_kit_column_tikuv_slice_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tikuv_slice_item_balance` CHANGE `is_kit` `is_kit` SMALLINT NULL DEFAULT '0';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
