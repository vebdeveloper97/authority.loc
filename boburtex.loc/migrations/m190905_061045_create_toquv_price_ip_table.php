<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_price_ip}}`.
 */
class m190905_061045_create_toquv_price_ip_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_price_ip}}', [
            'id' => $this->primaryKey(),
            'doc_number' => $this->string(50),
            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'add_info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%toquv_price_ip}}');
    }
}
