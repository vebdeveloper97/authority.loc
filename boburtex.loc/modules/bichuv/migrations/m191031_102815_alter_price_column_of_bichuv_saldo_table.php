<?php

use yii\db\Migration;

/**
 * Class m191031_102815_alter_price_column_of_bichuv_saldo_table
 */
class m191031_102815_alter_price_column_of_bichuv_saldo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bichuv_saldo', 'credit1', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_saldo', 'credit2', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_saldo', 'debit1', $this->decimal(20,3)->defaultValue(0));
        $this->alterColumn('bichuv_saldo', 'debit2', $this->decimal(20,3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('bichuv_saldo', 'credit1', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_saldo', 'credit2', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_saldo', 'debit1', $this->decimal(20,2)->defaultValue(0));
        $this->alterColumn('bichuv_saldo', 'debit2', $this->decimal(20,2)->defaultValue(0));
    }
}
