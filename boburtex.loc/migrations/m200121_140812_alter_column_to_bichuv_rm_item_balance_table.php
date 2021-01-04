<?php

use yii\db\Migration;

/**
 * Class m200121_140812_alter_column_to_bichuv_rm_item_balance_table
 */
class m200121_140812_alter_column_to_bichuv_rm_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE `bichuv_rm_item_balance` CHANGE `inventory` `inventory` DECIMAL(20,3) NULL DEFAULT '0';
                ALTER TABLE `bichuv_rm_item_balance` CHANGE `roll_inventory` `roll_inventory` DECIMAL(20,1) NULL DEFAULT '0';
                ALTER TABLE `bichuv_rm_item_balance` CHANGE `count` `count` DECIMAL(20,3) NULL DEFAULT '0';
                ALTER TABLE `bichuv_rm_item_balance` CHANGE `roll_count` `roll_count` DECIMAL(20,1) NULL DEFAULT '0';";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
