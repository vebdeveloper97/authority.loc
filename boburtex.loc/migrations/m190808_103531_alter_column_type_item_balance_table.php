<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_item_balance}}`.
 */
class m190808_103531_alter_column_type_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('toquv_item_balance','document_type', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('toquv_item_balance','document_type', $this->string(50));
    }
}
