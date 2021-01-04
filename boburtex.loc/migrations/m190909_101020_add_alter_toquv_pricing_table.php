<?php

use yii\db\Migration;

/**
 * Class m190909_101020_add_alter_toquv_pricing_table
 */
class m190909_101020_add_alter_toquv_pricing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
                ALTER TABLE `toquv_pricing` DROP FOREIGN KEY `fk-toquv_pricing-mato_id`;
                ALTER TABLE `toquv_pricing` ADD CONSTRAINT `fk-toquv_pricing-mato_id` 
                FOREIGN KEY (`mato_id`) REFERENCES `toquv_raw_materials`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190909_101020_add_alter_toquv_pricing_table cannot be reverted.\n";

        return false;
    }
}
