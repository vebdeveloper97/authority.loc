<?php

use yii\db\Migration;

/**
 * Class m200911_125606_add_cascade_table
 */
class m200911_125606_add_cascade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE `model_orders_items_pechat` DROP FOREIGN KEY `fk-model_orders_items_pechat-attachment_id`;
ALTER TABLE `model_orders_items_pechat` ADD  CONSTRAINT `fk-model_orders_items_pechat-attachment_id` FOREIGN KEY (`attachment_id`) REFERENCES `attachments`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = "ALTER TABLE `model_orders_items_pechat` DROP FOREIGN KEY `fk-model_orders_items_pechat-attachment_id`;
ALTER TABLE `model_orders_items_pechat` ADD  CONSTRAINT `fk-model_orders_items_pechat-attachment_id` FOREIGN KEY (`attachment_id`) REFERENCES `attachments`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
";
        $this->execute($sql);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200911_125606_add_cascade_table cannot be reverted.\n";

        return false;
    }
    */
}
