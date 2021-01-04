<?php

use yii\db\Migration;

/**
 * Class m200824_052611_alter_attachments_id_column_to_model_orders_attachment_relations_table
 */
class m200824_052611_alter_attachments_id_column_to_model_orders_attachment_relations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "ALTER TABLE `model_orders_attachment_relations` DROP FOREIGN KEY `fk-model_orders_attachment_relations-attachments_id`;
ALTER TABLE `model_orders_attachment_relations` ADD  CONSTRAINT `fk-model_orders_attachment_relations-attachments_id` FOREIGN KEY (`attachments_id`) REFERENCES `attachments`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200824_052611_alter_attachments_id_column_to_model_orders_attachment_relations_table cannot be reverted.\n";

        return false;
    }
    */
}
