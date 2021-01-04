<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_pricing_doc}}`.
 */
class m190901_151228_create_toquv_pricing_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_pricing_doc}}', [
            'id' => $this->primaryKey(),
            'doc_number' => $this->string(50),
            'doc_type' => $this->smallInteger()->defaultValue(1),
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
        $this->dropTable('{{%toquv_pricing_doc}}');
    }
}
